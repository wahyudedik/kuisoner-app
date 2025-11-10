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
}
