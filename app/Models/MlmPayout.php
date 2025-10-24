<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MlmPayout extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'payout_reference',
        'amount',
        'fee',
        'net_amount',
        'payment_method',
        'status',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'payment_details',
        'transaction_id',
        'notes',
        'rejection_reason',
        'processed_by',
        'requested_at',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'payment_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function commissions(): BelongsToMany
    {
        return $this->belongsToMany(MlmCommission::class, 'mlm_payout_commissions')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public static function generateReference(): string
    {
        return 'PO-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    public function calculateNetAmount(): float
    {
        return $this->amount - $this->fee;
    }

    public function markAsCompleted(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Mark commissions as paid
        foreach ($this->commissions as $commission) {
            $commission->markAsPaid();
        }
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
