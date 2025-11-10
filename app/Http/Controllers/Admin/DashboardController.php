<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\Result;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_questions' => Question::count(),
            'active_questions' => Question::active()->count(),
            'total_questionnaires' => Questionnaire::count(),
            'completed_questionnaires' => Questionnaire::where('status', 'completed')->count(),
            'in_progress_questionnaires' => Questionnaire::where('status', 'in_progress')->count(),
            'total_results' => Result::count(),
        ];

        $recentQuestionnaires = Questionnaire::with('result')
            ->latest()
            ->take(10)
            ->get();

        $categoryStats = Question::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category');

        // Chart data: Distribution of readiness categories
        $readinessDistribution = Result::selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->pluck('count', 'category')
            ->toArray();

        $readinessLabels = [
            'sangat_siap' => 'Sangat Siap',
            'cukup_siap' => 'Cukup Siap',
            'kurang_siap' => 'Kurang Siap',
            'tidak_siap' => 'Tidak Siap',
        ];

        $readinessData = [
            'labels' => array_values($readinessLabels),
            'data' => [
                $readinessDistribution['sangat_siap'] ?? 0,
                $readinessDistribution['cukup_siap'] ?? 0,
                $readinessDistribution['kurang_siap'] ?? 0,
                $readinessDistribution['tidak_siap'] ?? 0,
            ],
            'colors' => ['#10b981', '#f59e0b', '#f97316', '#ef4444'], // green, yellow, orange, red
        ];

        // Chart data: Average score per category (aspect)
        $categoryLabels = [
            'keuangan' => 'Keuangan',
            'emosional' => 'Emosional',
            'pendidikan' => 'Pendidikan',
            'pengasuhan_anak' => 'Pengasuhan Anak',
            'komunikasi' => 'Komunikasi',
            'sosial' => 'Sosial',
            'tanggung_jawab' => 'Tanggung Jawab',
        ];

        $results = Result::whereNotNull('category_scores')->get();
        $categoryAverages = [];
        
        foreach (array_keys($categoryLabels) as $category) {
            $scores = [];
            foreach ($results as $result) {
                $categoryScores = $result->category_scores ?? [];
                if (isset($categoryScores[$category]['percentage'])) {
                    $scores[] = $categoryScores[$category]['percentage'];
                }
            }
            $categoryAverages[$category] = count($scores) > 0 ? round(array_sum($scores) / count($scores), 2) : 0;
        }

        $aspectData = [
            'labels' => array_values($categoryLabels),
            'data' => array_values($categoryAverages),
            'color' => '#3b82f6', // blue
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'recentQuestionnaires', 
            'categoryStats',
            'readinessData',
            'aspectData'
        ));
    }
}
