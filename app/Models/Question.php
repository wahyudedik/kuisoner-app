<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'question_text',
        'category',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    /**
     * Get all responses for this question
     */
    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
    }

    /**
     * Get answer options
     */
    public static function getAnswerOptions(): array
    {
        return [
            'a' => 'Sangat siap',
            'b' => 'Cukup siap',
            'c' => 'Kurang siap',
            'd' => 'Tidak siap',
        ];
    }

    /**
     * Get score for answer
     */
    public static function getScoreForAnswer(string $answer): int
    {
        return match ($answer) {
            'a' => 4,
            'b' => 3,
            'c' => 2,
            'd' => 1,
            default => 0,
        };
    }

    /**
     * Scope for active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Ensure only 70 active questions (keep the first 70 by order)
     */
    public static function ensureMaxActiveQuestions(int $maxActive = 70): array
    {
        $activeQuestions = self::where('is_active', true)
            ->orderBy('order')
            ->get();

        $totalActive = $activeQuestions->count();
        $deactivatedCount = 0;

        if ($totalActive > $maxActive) {
            // Deactivate questions beyond the max limit
            $questionsToDeactivate = $activeQuestions->slice($maxActive);
            
            foreach ($questionsToDeactivate as $question) {
                $question->update(['is_active' => false]);
                $deactivatedCount++;
            }
        }

        return [
            'total_active' => min($totalActive, $maxActive),
            'deactivated_count' => $deactivatedCount,
            'exceeds_limit' => $totalActive > $maxActive,
        ];
    }

    /**
     * Get count of active questions
     */
    public static function getActiveCount(): int
    {
        return self::where('is_active', true)->count();
    }
}
