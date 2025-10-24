<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\MlmNetwork;
use App\Models\MlmCommission;
use App\Models\MlmCommissionSetting;
use App\Models\MlmGenealogy;
use Illuminate\Support\Facades\DB;

class MlmCommissionService
{
    /**
     * คำนวณและสร้างค่าคอมมิชชั่นจากยอดขาย
     */
    public function calculateCommissionsFromSale(User $user, Order $order): array
    {
        return DB::transaction(function () use ($user, $order) {
            $commissions = [];
            $amount = $order->total;

            // 1. Direct Commission (ค่าคอมมิชชั่นโดยตรง)
            $commissions = array_merge($commissions, $this->calculateDirectCommission($user, $order, $amount));

            // 2. Indirect Commission (ค่าคอมมิชชั่นทางอ้อม - หลายระดับ)
            $commissions = array_merge($commissions, $this->calculateIndirectCommission($user, $order, $amount));

            // 3. Binary Commission (ค่าคอมมิชชั่นจากระบบ Binary)
            $binaryCommission = $this->calculateBinaryCommission($user, $amount);
            if ($binaryCommission) {
                $commissions[] = $binaryCommission;
            }

            // อัพเดทยอดขาย
            $this->updatePersonalSales($user->id, $amount);

            return $commissions;
        });
    }

    /**
     * คำนวณค่าคอมมิชชั่นโดยตรง
     */
    private function calculateDirectCommission(User $user, Order $order, float $amount): array
    {
        $commissions = [];
        $network = MlmNetwork::where('user_id', $user->id)->first();

        if (!$network || !$network->sponsor_id) {
            return $commissions;
        }

        $setting = MlmCommissionSetting::active()
            ->byType('direct')
            ->byLevel(1)
            ->first();

        if ($setting) {
            $commissionAmount = $setting->calculateCommission($amount);

            if ($commissionAmount > 0) {
                $commission = MlmCommission::create([
                    'user_id' => $network->sponsor_id,
                    'from_user_id' => $user->id,
                    'order_id' => $order->id,
                    'commission_setting_id' => $setting->id,
                    'type' => 'direct',
                    'level' => 1,
                    'amount' => $commissionAmount,
                    'base_amount' => $amount,
                    'percentage' => $setting->percentage,
                    'status' => 'pending',
                    'earned_at' => now(),
                ]);

                $commissions[] = $commission;
            }
        }

        return $commissions;
    }

    /**
     * คำนวณค่าคอมมิชชั่นทางอ้อม (หลายระดับ)
     */
    private function calculateIndirectCommission(User $user, Order $order, float $amount): array
    {
        $commissions = [];
        $ancestors = MlmGenealogy::where('user_id', $user->id)
            ->where('depth', '>', 0)
            ->orderBy('depth')
            ->get();

        foreach ($ancestors as $genealogy) {
            $setting = MlmCommissionSetting::active()
                ->byType('indirect')
                ->byLevel($genealogy->depth)
                ->first();

            if ($setting) {
                $commissionAmount = $setting->calculateCommission($amount);

                if ($commissionAmount > 0) {
                    $commission = MlmCommission::create([
                        'user_id' => $genealogy->ancestor_id,
                        'from_user_id' => $user->id,
                        'order_id' => $order->id,
                        'commission_setting_id' => $setting->id,
                        'type' => 'indirect',
                        'level' => $genealogy->depth,
                        'amount' => $commissionAmount,
                        'base_amount' => $amount,
                        'percentage' => $setting->percentage,
                        'status' => 'pending',
                        'earned_at' => now(),
                    ]);

                    $commissions[] = $commission;
                }
            }

            // จำกัดไม่เกิน 10 ระดับ
            if ($genealogy->depth >= 10) {
                break;
            }
        }

        return $commissions;
    }

    /**
     * คำนวณค่าคอมมิชชั่นแบบ Binary
     */
    private function calculateBinaryCommission(User $user, float $amount): ?MlmCommission
    {
        $network = MlmNetwork::where('user_id', $user->id)->first();

        if (!$network || !$network->parent_id) {
            return null;
        }

        $parentNetwork = MlmNetwork::where('user_id', $network->parent_id)->first();

        if (!$parentNetwork || !$parentNetwork->hasBinaryBalance()) {
            return null;
        }

        $setting = MlmCommissionSetting::active()
            ->byType('binary')
            ->first();

        if (!$setting) {
            return null;
        }

        $weakerLeg = $parentNetwork->getWeakerLegSales();
        $commissionAmount = $setting->calculateCommission($weakerLeg);

        if ($commissionAmount > 0) {
            return MlmCommission::create([
                'user_id' => $network->parent_id,
                'from_user_id' => $user->id,
                'order_id' => null,
                'commission_setting_id' => $setting->id,
                'type' => 'binary',
                'level' => 1,
                'amount' => $commissionAmount,
                'base_amount' => $weakerLeg,
                'percentage' => $setting->percentage,
                'status' => 'pending',
                'earned_at' => now(),
            ]);
        }

        return null;
    }

    /**
     * อัพเดทยอดขายส่วนตัว
     */
    private function updatePersonalSales(int $userId, float $amount): void
    {
        $network = MlmNetwork::where('user_id', $userId)->first();

        if ($network) {
            $network->personal_sales += $amount;
            $network->save();

            // อัพเดทสถิติของบรรพบุรุษ
            $networkService = new MlmNetworkService();
            $networkService->updateAncestorsStatistics($userId);
        }
    }

    /**
     * อนุมัติค่าคอมมิชชั่น
     */
    public function approveCommissions(array $commissionIds): int
    {
        return MlmCommission::whereIn('id', $commissionIds)
            ->where('status', 'pending')
            ->update([
                'status' => 'approved',
                'updated_at' => now(),
            ]);
    }

    /**
     * ยกเลิกค่าคอมมิชชั่น
     */
    public function cancelCommissions(array $commissionIds, string $reason = null): int
    {
        return MlmCommission::whereIn('id', $commissionIds)
            ->whereIn('status', ['pending', 'approved'])
            ->update([
                'status' => 'cancelled',
                'description' => $reason,
                'updated_at' => now(),
            ]);
    }
}
