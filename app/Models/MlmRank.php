<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MlmRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_th',
        'description',
        'level',
        'min_personal_sales',
        'min_group_sales',
        'min_direct_referrals',
        'min_qualified_legs',
        'commission_rate',
        'monthly_bonus',
        'badge_icon',
        'badge_color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'level' => 'integer',
        'min_personal_sales' => 'decimal:2',
        'min_group_sales' => 'decimal:2',
        'min_direct_referrals' => 'integer',
        'min_qualified_legs' => 'integer',
        'commission_rate' => 'decimal:2',
        'monthly_bonus' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get all users with this rank.
     */
    public function userRanks(): HasMany
    {
        return $this->hasMany(MlmUserRank::class, 'rank_id');
    }

    /**
     * Get current users with this rank.
     */
    public function currentUsers(): HasMany
    {
        return $this->hasMany(MlmUserRank::class, 'rank_id')
            ->where('is_current', true);
    }

    /**
     * Check if user qualifies for this rank.
     */
    public function userQualifies(User $user): bool
    {
        $network = MlmNetwork::where('user_id', $user->id)->first();

        if (!$network) {
            return false;
        }

        // ตรวจสอบยอดขายส่วนตัว
        if ($network->personal_sales < $this->min_personal_sales) {
            return false;
        }

        // ตรวจสอบยอดขายกลุ่ม
        if ($network->group_sales < $this->min_group_sales) {
            return false;
        }

        // ตรวจสอบจำนวนคนแนะนำโดยตรง
        $directReferrals = MlmNetwork::where('sponsor_id', $user->id)
            ->where('is_active', true)
            ->count();

        if ($directReferrals < $this->min_direct_referrals) {
            return false;
        }

        // ตรวจสอบจำนวน qualified legs
        if ($this->min_qualified_legs > 0) {
            $qualifiedLegs = $this->countQualifiedLegs($user);
            if ($qualifiedLegs < $this->min_qualified_legs) {
                return false;
            }
        }

        return true;
    }

    /**
     * Count qualified legs for user.
     */
    private function countQualifiedLegs(User $user): int
    {
        // Logic สำหรับนับขาที่ผ่านเกณฑ์
        // ตัวอย่าง: ขาที่มียอดขายถึงเกณฑ์ขั้นต่ำ
        $minLegSales = $this->min_group_sales * 0.3; // 30% ของยอดกลุ่ม

        $network = MlmNetwork::where('user_id', $user->id)->first();
        if (!$network) {
            return 0;
        }

        $qualified = 0;
        if ($network->left_sales >= $minLegSales) {
            $qualified++;
        }
        if ($network->right_sales >= $minLegSales) {
            $qualified++;
        }

        return $qualified;
    }

    /**
     * Scope for active ranks.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered ranks.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('level');
    }
}
