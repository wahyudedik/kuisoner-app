<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        // Ensure user owns this result
        if ($result->questionnaire->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $questionnaire = $result->questionnaire;
        $categoryScores = $result->category_scores ?? [];

        $categoryLabels = [
            'keuangan' => 'Keuangan',
            'emosional' => 'Emosional',
            'pendidikan' => 'Pendidikan',
            'pengasuhan_anak' => 'Pengasuhan Anak',
            'komunikasi' => 'Komunikasi & Konflik',
            'sosial' => 'Sosial & Lingkungan',
            'tanggung_jawab' => 'Tanggung Jawab',
        ];

        return view('results.show', compact('result', 'questionnaire', 'categoryScores', 'categoryLabels'));
    }
}
