<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Result::with(['questionnaire.user'])->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by user name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('questionnaire.user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('questionnaire', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $results = $query->paginate(15)->withQueryString();

        $categoryLabels = [
            'sangat_siap' => 'Sangat Siap',
            'cukup_siap' => 'Cukup Siap',
            'kurang_siap' => 'Kurang Siap',
        ];

        return view('admin.results.index', compact('results', 'categoryLabels'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Result $result)
    {
        $result->load(['questionnaire.user', 'questionnaire.responses.question']);

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

        return view('admin.results.show', compact('result', 'questionnaire', 'categoryScores', 'categoryLabels'));
    }
}

