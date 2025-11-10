<?php

namespace App\Services;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Result;

class ScoringService
{
    /**
     * Calculate score for answer
     */
    public static function getScoreForAnswer(string $answer): int
    {
        return Question::getScoreForAnswer($answer);
    }

    /**
     * Calculate total score from responses
     */
    public function calculateTotalScore(Questionnaire $questionnaire): int
    {
        return $questionnaire->getTotalScore();
    }

    /**
     * Calculate score by category
     */
    public function calculateScoreByCategory(Questionnaire $questionnaire, string $category): int
    {
        return $questionnaire->getScoreByCategory($category);
    }

    /**
     * Calculate all category scores
     */
    public function calculateCategoryScores(Questionnaire $questionnaire): array
    {
        $categories = [
            'keuangan',
            'emosional',
            'pendidikan',
            'pengasuhan_anak',
            'komunikasi',
            'sosial',
            'tanggung_jawab',
        ];

        $categoryScores = [];
        foreach ($categories as $category) {
            $score = $this->calculateScoreByCategory($questionnaire, $category);
            $maxScore = Question::active()->byCategory($category)->count() * 4;
            $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100, 2) : 0;
            
            $categoryScores[$category] = [
                'score' => $score,
                'max_score' => $maxScore,
                'percentage' => $percentage,
            ];
        }

        return $categoryScores;
    }

    /**
     * Determine category based on total score
     */
    public function determineCategory(int $totalScore, int $maxScore = 280): string
    {
        return Result::determineCategory($totalScore, $maxScore);
    }

    /**
     * Calculate percentage
     */
    public function calculatePercentage(int $totalScore, int $maxScore = 280): float
    {
        return Result::calculatePercentage($totalScore, $maxScore);
    }

    /**
     * Generate suggestions based on results
     */
    public function generateSuggestions(Result $result): string
    {
        $category = $result->category;
        $categoryScores = $result->category_scores ?? [];
        
        $suggestions = [];
        
        // General suggestions based on category
        switch ($category) {
            case 'sangat_siap':
                $suggestions[] = "Selamat! Anda memiliki tingkat kesiapan yang sangat baik untuk menikah.";
                $suggestions[] = "Pertahankan komunikasi yang baik dengan pasangan dan terus kembangkan aspek-aspek positif yang sudah ada.";
                break;
                
            case 'cukup_siap':
                $suggestions[] = "Anda memiliki tingkat kesiapan yang cukup, namun masih ada beberapa aspek yang perlu ditingkatkan.";
                $suggestions[] = "Fokus pada aspek yang masih perlu perbaikan dan diskusikan dengan pasangan untuk meningkatkan kesiapan bersama.";
                break;
                
            case 'kurang_siap':
                $suggestions[] = "Anda perlu meningkatkan kesiapan di berbagai aspek sebelum menikah.";
                $suggestions[] = "Pertimbangkan untuk menunda pernikahan dan fokus pada peningkatan aspek-aspek yang masih kurang.";
                $suggestions[] = "Konsultasikan dengan pasangan dan pertimbangkan konseling pra-nikah untuk membantu persiapan.";
                break;
                
            case 'tidak_siap':
                $suggestions[] = "Berdasarkan hasil kuesioner, tingkat kesiapan Anda untuk menikah masih perlu ditingkatkan secara signifikan.";
                $suggestions[] = "Sangat disarankan untuk menunda pernikahan dan fokus pada pengembangan diri di berbagai aspek kehidupan.";
                $suggestions[] = "Pertimbangkan untuk mengikuti program konseling pra-nikah, mengikuti pelatihan atau seminar tentang persiapan pernikahan, dan melakukan diskusi mendalam dengan pasangan tentang rencana masa depan.";
                $suggestions[] = "Bangun fondasi yang kuat terlebih dahulu sebelum mengambil langkah menikah.";
                break;
        }
        
        // Specific suggestions based on category scores
        $lowCategories = [];
        foreach ($categoryScores as $cat => $data) {
            if (isset($data['percentage']) && $data['percentage'] < 60) {
                $lowCategories[] = $cat;
            }
        }
        
        if (!empty($lowCategories)) {
            $suggestions[] = "\nAspek yang perlu ditingkatkan:";
            foreach ($lowCategories as $cat) {
                $catLabel = ucfirst(str_replace('_', ' ', $cat));
                $suggestions[] = "- {$catLabel}: Fokus pada peningkatan kesiapan di aspek ini melalui diskusi dengan pasangan, membaca literatur, atau mengikuti program persiapan pernikahan.";
            }
        }
        
        return implode("\n\n", $suggestions);
    }

    /**
     * Process and save results
     */
    public function processResults(Questionnaire $questionnaire): Result
    {
        $totalScore = $this->calculateTotalScore($questionnaire);
        $maxScore = Question::active()->count() * 4;
        $percentage = $this->calculatePercentage($totalScore, $maxScore);
        $category = $this->determineCategory($totalScore, $maxScore);
        $categoryScores = $this->calculateCategoryScores($questionnaire);
        
        // Create or update result
        $result = Result::updateOrCreate(
            ['questionnaire_id' => $questionnaire->id],
            [
                'total_score' => $totalScore,
                'percentage' => $percentage,
                'category' => $category,
                'category_scores' => $categoryScores,
            ]
        );
        
        // Generate suggestions
        $suggestions = $this->generateSuggestions($result);
        $result->update(['suggestions' => $suggestions]);
        
        // Mark questionnaire as completed
        $questionnaire->markAsCompleted();
        
        return $result;
    }
}

