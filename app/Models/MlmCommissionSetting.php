<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MlmCommissionSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'level',
        'percentage',
        'flat_amount',
        'min_sales',
        'max_commission',
        'is_active',
        'conditions',
    ];

    protected $casts = [
        'level' => 'integer',
        'percentage' => 'decimal:2',
        'flat_amount' => 'decimal:2',
        'min_sales' => 'decimal:2',
        'max_commission' => 'decimal:2',
        'is_active' => 'boolean',
        'conditions' => 'array',
    ];

    public function commissions(): HasMany
    {
        return $this->hasMany(MlmCommission::class);
    }

    public function calculateCommission(float $amount): float
    {
        if ($amount < $this->min_sales) {
            return 0;
        }

        $commission = $this->flat_amount + ($amount * $this->percentage / 100);

        if ($this->max_commission && $commission > $this->max_commission) {
            $commission = $this->max_commission;
        }

        return round($commission, 2);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByLevel($query, int $level)
    {
        return $query->where('level', $level);
    }
}
