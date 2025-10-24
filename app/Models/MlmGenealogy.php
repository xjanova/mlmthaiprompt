<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MlmGenealogy extends Model
{
    use HasFactory;

    protected $table = 'mlm_genealogy';

    protected $fillable = [
        'user_id',
        'ancestor_id',
        'depth',
        'path',
    ];

    protected $casts = [
        'depth' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ancestor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ancestor_id');
    }

    /**
     * Get all ancestors for a user.
     */
    public static function getAncestors(int $userId): array
    {
        return self::where('user_id', $userId)
            ->orderBy('depth')
            ->pluck('ancestor_id')
            ->toArray();
    }

    /**
     * Get all descendants for a user.
     */
    public static function getDescendants(int $userId): array
    {
        return self::where('ancestor_id', $userId)
            ->pluck('user_id')
            ->toArray();
    }

    /**
     * Check if user is descendant of another user.
     */
    public static function isDescendant(int $userId, int $ancestorId): bool
    {
        return self::where('user_id', $userId)
            ->where('ancestor_id', $ancestorId)
            ->exists();
    }

    /**
     * Build genealogy path when new user joins.
     */
    public static function buildPath(int $userId, int $parentId): void
    {
        // Add self
        self::create([
            'user_id' => $userId,
            'ancestor_id' => $userId,
            'depth' => 0,
            'path' => (string)$userId,
        ]);

        // Add all ancestors from parent
        $parentGenealogy = self::where('user_id', $parentId)->get();

        foreach ($parentGenealogy as $gen) {
            self::create([
                'user_id' => $userId,
                'ancestor_id' => $gen->ancestor_id,
                'depth' => $gen->depth + 1,
                'path' => $gen->path . '/' . $userId,
            ]);
        }
    }
}
