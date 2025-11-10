<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuestionnaireController extends Controller
{
    protected $scoringService;

    public function __construct(ScoringService $scoringService)
    {
        $this->scoringService = $scoringService;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('questionnaires.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $questionnaire = Questionnaire::create([
            'user_id' => auth()->id(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => 'in_progress',
            'started_at' => now(),
        ]);

        return redirect()->route('questionnaires.show', $questionnaire)
            ->with('success', 'Kuesioner berhasil dibuat. Silakan jawab semua pertanyaan.');
    }

    /**
     * Display the specified resource (form jawab pertanyaan).
     */
    public function show(Questionnaire $questionnaire)
    {
        // Ensure user owns this questionnaire
        if ($questionnaire->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // If already completed, redirect to results
        if ($questionnaire->isCompleted()) {
            return redirect()->route('results.show', $questionnaire->result->id)
                ->with('info', 'Kuesioner ini sudah selesai. Berikut hasilnya.');
        }

        $questions = Question::active()->ordered()->get();
        $questionsByCategory = $questions->groupBy('category');

        $categoryLabels = [
            'keuangan' => 'Aspek Keuangan',
            'emosional' => 'Aspek Emosional',
            'pendidikan' => 'Aspek Pendidikan',
            'pengasuhan_anak' => 'Aspek Pengasuhan Anak',
            'komunikasi' => 'Aspek Komunikasi & Konflik',
            'sosial' => 'Aspek Sosial & Lingkungan',
            'tanggung_jawab' => 'Aspek Tanggung Jawab',
        ];

        // Get existing responses
        $responses = $questionnaire->responses()->pluck('answer', 'question_id')->toArray();

        return view('questionnaires.show', compact('questionnaire', 'questionsByCategory', 'categoryLabels', 'responses'));
    }

    /**
     * Update the specified resource in storage (save answers).
     */
    public function update(Request $request, Questionnaire $questionnaire)
    {
        // Ensure user owns this questionnaire
        if ($questionnaire->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // If already completed, don't allow updates
        if ($questionnaire->isCompleted()) {
            return redirect()->route('results.show', $questionnaire->result->id)
                ->with('error', 'Kuesioner ini sudah selesai dan tidak dapat diubah.');
        }

        $validated = $request->validate([
            'answers' => 'required|array',
            'answers.*' => 'required|in:a,b,c,d',
        ]);

        $questions = Question::active()->ordered()->get();
        
        // Validate all questions are answered
        if (count($validated['answers']) !== $questions->count()) {
            return back()->withErrors(['answers' => 'Semua pertanyaan harus dijawab.'])->withInput();
        }

        DB::transaction(function () use ($questionnaire, $validated, $questions) {
            // Delete existing responses
            $questionnaire->responses()->delete();

            // Create new responses
            foreach ($validated['answers'] as $questionId => $answer) {
                $question = $questions->find($questionId);
                if ($question) {
                    $score = Question::getScoreForAnswer($answer);
                    
                    $questionnaire->responses()->create([
                        'question_id' => $questionId,
                        'answer' => $answer,
                        'score' => $score,
                    ]);
                }
            }

            // Process results
            $result = $this->scoringService->processResults($questionnaire);

            // Store result ID in session for redirect
            session(['last_result_id' => $result->id]);
        });

        return redirect()->route('results.show', session('last_result_id'))
            ->with('success', 'Kuesioner berhasil diselesaikan! Berikut hasil analisis Anda.');
    }
}
