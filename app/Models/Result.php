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
            'tidak_siap' => 'Tidak Siap',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Determine category based on score
     * Range: 70-280 poin (70 soal × 1-4 poin per soal)
     * Berdasarkan contoh:
     * - 280/280 (100%) → Sangat Siap
     * - 200/280 (71%) → Cukup Siap
     * - 140/280 (50%) → Kurang Siap
     * - 70/280 (25%) → Tidak Siap
     * 
     * Kategori:
     * - Sangat Siap: >=72% (≥202 poin dari 280)
     * - Cukup Siap: 50-71% (140-201 poin dari 280)
     * - Kurang Siap: 25-49% (70-139 poin dari 280)
     * - Tidak Siap: <25% (<70 poin dari 280)
     */
    public static function determineCategory(int $totalScore, int $maxScore = 280): string
    {
        // Calculate percentage
        $percentage = $maxScore > 0 ? ($totalScore / $maxScore) * 100 : 0;
        
        // Determine category based on percentage
        if ($percentage >= 72) {
            return 'sangat_siap';
        } elseif ($percentage >= 50) {
            return 'cukup_siap';
        } elseif ($percentage >= 25) {
            return 'kurang_siap';
        } else {
            return 'tidak_siap';
        }
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
