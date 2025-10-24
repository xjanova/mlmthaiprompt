<?php

namespace App\Services;

use App\Models\User;
use App\Models\MlmNetwork;
use App\Models\MlmGenealogy;
use Illuminate\Support\Facades\DB;

class MlmNetworkService
{
    /**
     * เพิ่มสมาชิกใหม่เข้าสู่เครือข่าย MLM
     */
    public function addMember(User $user, int $sponsorId, ?int $parentId = null, ?string $position = null): MlmNetwork
    {
        return DB::transaction(function () use ($user, $sponsorId, $parentId, $position) {
            $sponsor = User::findOrFail($sponsorId);
            $sponsorNetwork = MlmNetwork::where('user_id', $sponsorId)->first();

            // ถ้าไม่ระบุ parent ให้ใช้ sponsor เป็น parent
            $parentId = $parentId ?? $sponsorId;
            $parentNetwork = MlmNetwork::where('user_id', $parentId)->first();

            // คำนวณ level
            $level = $parentNetwork ? $parentNetwork->level + 1 : 0;

            // สร้าง network record
            $network = MlmNetwork::create([
                'user_id' => $user->id,
                'sponsor_id' => $sponsorId,
                'parent_id' => $parentId,
                'position' => $position,
                'network_type' => 'binary',
                'level' => $level,
                'is_active' => true,
                'joined_at' => now(),
            ]);

            // สร้าง genealogy
            MlmGenealogy::buildPath($user->id, $parentId);

            // อัพเดทสถิติของ parent และบรรพบุรุษ
            $this->updateAncestorsStatistics($user->id);

            return $network;
        });
    }

    /**
     * หาตำแหน่งว่างถัดไปในโครงสร้าง Binary
     */
    public function findNextAvailablePosition(int $userId): array
    {
        $network = MlmNetwork::where('user_id', $userId)->first();

        if (!$network) {
            return ['parent_id' => $userId, 'position' => 'left'];
        }

        // ตรวจสอบตำแหน่งซ้าย
        $leftChild = MlmNetwork::where('parent_id', $userId)
            ->where('position', 'left')
            ->first();

        if (!$leftChild) {
            return ['parent_id' => $userId, 'position' => 'left'];
        }

        // ตรวจสอบตำแหน่งขวา
        $rightChild = MlmNetwork::where('parent_id', $userId)
            ->where('position', 'right')
            ->first();

        if (!$rightChild) {
            return ['parent_id' => $userId, 'position' => 'right'];
        }

        // ถ้าเต็มแล้ว ให้หาในระดับถัดไป (Level Order Traversal)
        return $this->findNextAvailablePosition($leftChild->user_id);
    }

    /**
     * อัพเดทสถิติของบรรพบุรุษทั้งหมด
     */
    public function updateAncestorsStatistics(int $userId): void
    {
        $ancestors = MlmGenealogy::getAncestors($userId);

        foreach ($ancestors as $ancestorId) {
            $network = MlmNetwork::where('user_id', $ancestorId)->first();
            if ($network) {
                $network->updateLegStatistics();
            }
        }
    }

    /**
     * คำนวณยอดขายกลุ่มทั้งหมด
     */
    public function calculateTotalGroupSales(int $userId): float
    {
        $network = MlmNetwork::where('user_id', $userId)->first();
        if (!$network) {
            return 0;
        }

        $descendants = MlmGenealogy::getDescendants($userId);
        $totalSales = $network->personal_sales;

        foreach ($descendants as $descendantId) {
            $descendantNetwork = MlmNetwork::where('user_id', $descendantId)->first();
            if ($descendantNetwork) {
                $totalSales += $descendantNetwork->personal_sales;
            }
        }

        return $totalSales;
    }

    /**
     * ดึงโครงสร้างเครือข่าย (Tree structure)
     */
    public function getNetworkTree(int $userId, int $depth = 5): array
    {
        $network = MlmNetwork::with(['user', 'downlines'])->where('user_id', $userId)->first();

        if (!$network || $depth <= 0) {
            return [];
        }

        return $this->buildTree($network, $depth);
    }

    /**
     * สร้างโครงสร้าง tree แบบ recursive
     */
    private function buildTree(MlmNetwork $network, int $depth): array
    {
        $node = [
            'id' => $network->user_id,
            'name' => $network->user->name,
            'email' => $network->user->email,
            'position' => $network->position,
            'level' => $network->level,
            'personal_sales' => $network->personal_sales,
            'group_sales' => $network->group_sales,
            'left_sales' => $network->left_sales,
            'right_sales' => $network->right_sales,
            'is_active' => $network->is_active,
            'children' => [],
        ];

        if ($depth > 1) {
            foreach ($network->downlines as $downline) {
                $node['children'][] = $this->buildTree($downline, $depth - 1);
            }
        }

        return $node;
    }
}
