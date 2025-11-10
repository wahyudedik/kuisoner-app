<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'questionnaire_id',
        'total_score',
        'percentage',
        'category',
        'category_scores',
        'suggestions',
    ];

    protected $casts = [
        'total_score' => 'integer',
        'percentage' => 'decimal:2',
        'category_scores' => 'array',
    ];

    /**
     * Get the questionnaire that owns this result
     */
    public function questionnaire(): BelongsTo
    {
        return $this->belongsTo(Questionnaire::class);
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'sangat_siap' => 'Sangat Siap',
            'cukup_siap' => 'Cukup Siap',
            'kurang_siap' => 'Kurang Siap',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Determine category based on score
     */
    public static function determineCategory(int $totalScore): string
    {
        if ($totalScore >= 87 && $totalScore <= 140) {
            return 'sangat_siap';
        } elseif ($totalScore >= 61 && $totalScore <= 86) {
            return 'cukup_siap';
        } elseif ($totalScore >= 35 && $totalScore <= 60) {
            return 'kurang_siap';
        }
        
        return 'kurang_siap'; // Default untuk skor di bawah 35
    }

    /**
     * Calculate percentage from total score
     */
    public static function calculatePercentage(int $totalScore, int $maxScore = 280): float
    {
        if ($maxScore === 0) {
            return 0;
        }
        
        return round(($totalScore / $maxScore) * 100, 2);
    }
}
