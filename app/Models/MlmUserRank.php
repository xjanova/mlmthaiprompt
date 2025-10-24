<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MlmUserRank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rank_id',
        'achieved_at',
        'is_current',
        'notes',
    ];

    protected $casts = [
        'achieved_at' => 'datetime',
        'is_current' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rank(): BelongsTo
    {
        return $this->belongsTo(MlmRank::class);
    }

    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
