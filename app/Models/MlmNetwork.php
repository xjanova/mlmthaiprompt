<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MlmNetwork extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sponsor_id',
        'parent_id',
        'position',
        'network_type',
        'level',
        'left_count',
        'right_count',
        'left_sales',
        'right_sales',
        'personal_sales',
        'group_sales',
        'is_active',
        'joined_at',
    ];

    protected $casts = [
        'left_count' => 'integer',
        'right_count' => 'integer',
        'left_sales' => 'decimal:2',
        'right_sales' => 'decimal:2',
        'personal_sales' => 'decimal:2',
        'group_sales' => 'decimal:2',
        'is_active' => 'boolean',
        'joined_at' => 'datetime',
    ];

    /**
     * Get the user that owns the network.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the sponsor (ผู้แนะนำ).
     */
    public function sponsor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    /**
     * Get the parent in the network structure.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Get all direct downlines (ลูกโดยตรง).
     */
    public function downlines(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'user_id');
    }

    /**
     * Get left leg downlines.
     */
    public function leftLeg(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'user_id')
            ->where('position', 'left');
    }

    /**
     * Get right leg downlines.
     */
    public function rightLeg(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'user_id')
            ->where('position', 'right');
    }

    /**
     * Get all downlines by sponsor.
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(self::class, 'sponsor_id', 'user_id');
    }

    /**
     * Calculate total team sales.
     */
    public function calculateTeamSales(): float
    {
        return $this->left_sales + $this->right_sales + $this->personal_sales;
    }

    /**
     * Check if user has binary balance.
     */
    public function hasBinaryBalance(): bool
    {
        $min = min($this->left_sales, $this->right_sales);
        $max = max($this->left_sales, $this->right_sales);

        // ถ้าขาน้อยมีอย่างน้อย 40% ของขามาก = สมดุล
        return $max > 0 && ($min / $max) >= 0.4;
    }

    /**
     * Get weaker leg sales.
     */
    public function getWeakerLegSales(): float
    {
        return min($this->left_sales, $this->right_sales);
    }

    /**
     * Get stronger leg sales.
     */
    public function getStrongerLegSales(): float
    {
        return max($this->left_sales, $this->right_sales);
    }

    /**
     * Update leg counts and sales.
     */
    public function updateLegStatistics(): void
    {
        $leftLeg = $this->leftLeg()->first();
        $rightLeg = $this->rightLeg()->first();

        $this->left_count = $leftLeg ? $this->countDownlines($leftLeg->user_id) : 0;
        $this->right_count = $rightLeg ? $this->countDownlines($rightLeg->user_id) : 0;

        $this->left_sales = $leftLeg ? $this->calculateLegSales($leftLeg->user_id) : 0;
        $this->right_sales = $rightLeg ? $this->calculateLegSales($rightLeg->user_id) : 0;

        $this->group_sales = $this->left_sales + $this->right_sales + $this->personal_sales;

        $this->save();
    }

    /**
     * Count all downlines recursively.
     */
    private function countDownlines(int $userId): int
    {
        $count = 0;
        $downlines = self::where('parent_id', $userId)->get();

        foreach ($downlines as $downline) {
            $count++;
            $count += $this->countDownlines($downline->user_id);
        }

        return $count;
    }

    /**
     * Calculate leg sales recursively.
     */
    private function calculateLegSales(int $userId): float
    {
        $network = self::where('user_id', $userId)->first();
        if (!$network) {
            return 0;
        }

        $sales = $network->personal_sales;
        $downlines = self::where('parent_id', $userId)->get();

        foreach ($downlines as $downline) {
            $sales += $this->calculateLegSales($downline->user_id);
        }

        return $sales;
    }
}
