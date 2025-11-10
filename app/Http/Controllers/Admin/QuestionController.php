<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Exports\QuestionsExport;
use App\Exports\QuestionsTemplateExport;
use App\Imports\QuestionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Question::query();

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('question_text', 'like', '%' . $request->search . '%');
        }

        $questions = $query->ordered()->paginate(15);
        $categories = Question::select('category')->distinct()->pluck('category');

        return view('admin.questions.index', compact('questions', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = [
            'keuangan' => 'Keuangan',
            'emosional' => 'Emosional',
            'pendidikan' => 'Pendidikan',
            'pengasuhan_anak' => 'Pengasuhan Anak',
            'komunikasi' => 'Komunikasi & Konflik',
            'sosial' => 'Sosial & Lingkungan',
            'tanggung_jawab' => 'Tanggung Jawab',
        ];

        $maxOrder = Question::max('order') ?? 0;
        $activeCount = Question::getActiveCount();
        $maxActiveQuestions = 70;
        $canActivate = $activeCount < $maxActiveQuestions;

        return view('admin.questions.create', compact('categories', 'maxOrder', 'activeCount', 'maxActiveQuestions', 'canActivate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'category' => 'required|string|in:keuangan,emosional,pendidikan,pengasuhan_anak,komunikasi,sosial,tanggung_jawab',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $maxActiveQuestions = 70;
        $isActive = $request->has('is_active') ? true : false;
        $currentActiveCount = Question::getActiveCount();

        // If trying to activate a new question and already have 70 active
        if ($isActive && $currentActiveCount >= $maxActiveQuestions) {
            return redirect()->route('admin.questions.index')
                ->with('warning', "Peringatan: Maksimal 70 soal aktif telah tercapai. Saat ini sudah ada <strong>{$currentActiveCount}/70</strong> soal aktif. Pertanyaan baru akan dibuat dalam status tidak aktif. Soal ke-71 dan seterusnya akan otomatis dinonaktifkan.");
        }

        // Create question (will be inactive if exceeds limit)
        $question = Question::create([
            'question_text' => $validated['question_text'],
            'category' => $validated['category'],
            'order' => $validated['order'],
            'is_active' => ($isActive && $currentActiveCount < $maxActiveQuestions) ? true : false,
        ]);

        // Ensure max active questions
        $result = Question::ensureMaxActiveQuestions($maxActiveQuestions);

        $message = 'Pertanyaan berhasil ditambahkan.';
        if ($result['deactivated_count'] > 0) {
            $message = "Pertanyaan berhasil ditambahkan. <strong>{$result['deactivated_count']} soal dinonaktifkan</strong> karena melebihi batas 70 soal aktif. Soal ke-71 dan seterusnya otomatis dinonaktifkan berdasarkan urutan.";
            return redirect()->route('admin.questions.index')
                ->with('warning', $message);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        return view('admin.questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Question $question)
    {
        $categories = [
            'keuangan' => 'Keuangan',
            'emosional' => 'Emosional',
            'pendidikan' => 'Pendidikan',
            'pengasuhan_anak' => 'Pengasuhan Anak',
            'komunikasi' => 'Komunikasi & Konflik',
            'sosial' => 'Sosial & Lingkungan',
            'tanggung_jawab' => 'Tanggung Jawab',
        ];

        $activeCount = Question::getActiveCount();
        $maxActiveQuestions = 70;
        $canActivate = ($question->is_active) || ($activeCount < $maxActiveQuestions);

        return view('admin.questions.edit', compact('question', 'categories', 'activeCount', 'maxActiveQuestions', 'canActivate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string|max:1000',
            'category' => 'required|string|in:keuangan,emosional,pendidikan,pengasuhan_anak,komunikasi,sosial,tanggung_jawab',
            'order' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $maxActiveQuestions = 70;
        $wantToActivate = $request->has('is_active') ? true : false;
        $currentlyActive = $question->is_active;
        $currentActiveCount = Question::getActiveCount();

        // If trying to activate and already have 70 active (and this question is not currently active)
        if ($wantToActivate && !$currentlyActive && $currentActiveCount >= $maxActiveQuestions) {
            return redirect()->route('admin.questions.index')
                ->with('warning', "Peringatan: Maksimal 70 soal aktif. Saat ini sudah ada <strong>{$currentActiveCount}/70</strong> soal aktif. Tidak dapat mengaktifkan soal ini karena sudah mencapai batas maksimal.");
        }

        // Update question
        $question->update([
            'question_text' => $validated['question_text'],
            'category' => $validated['category'],
            'order' => $validated['order'],
            'is_active' => $wantToActivate,
        ]);

        // Ensure max active questions (will deactivate excess questions)
        $result = Question::ensureMaxActiveQuestions($maxActiveQuestions);

        $message = 'Pertanyaan berhasil diperbarui.';
        if ($result['deactivated_count'] > 0) {
            $message = "Pertanyaan berhasil diperbarui. <strong>{$result['deactivated_count']} soal dinonaktifkan</strong> karena melebihi batas 70 soal aktif. Soal ke-71 dan seterusnya otomatis dinonaktifkan berdasarkan urutan.";
            return redirect()->route('admin.questions.index')
                ->with('warning', $message);
        }

        return redirect()->route('admin.questions.index')
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return redirect()->route('admin.questions.index')
            ->with('success', 'Pertanyaan berhasil dihapus.');
    }

    /**
     * Export questions to Excel
     */
    public function export()
    {
        $filename = 'daftar-pertanyaan-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(new QuestionsExport, $filename);
    }

    /**
     * Show import form
     */
    public function showImport()
    {
        return view('admin.questions.import');
    }

    /**
     * Download template Excel
     */
    public function downloadTemplate()
    {
        $filename = 'template-import-pertanyaan.xlsx';
        
        return Excel::download(new QuestionsTemplateExport, $filename);
    }

    /**
     * Import questions from Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        try {
            $maxActiveQuestions = 70;
            $activeCountBefore = Question::getActiveCount();
            
            // Import questions
            Excel::import(new QuestionsImport, $request->file('file'));
            
            // Ensure max active questions (deactivate excess)
            $result = Question::ensureMaxActiveQuestions($maxActiveQuestions);
            
            $activeCountAfter = Question::getActiveCount();
            $message = "Pertanyaan berhasil diimpor dari Excel.";
            
            if ($result['deactivated_count'] > 0) {
                $message = "Pertanyaan berhasil diimpor. <strong>{$result['deactivated_count']} soal dinonaktifkan</strong> karena melebihi batas maksimal 70 soal aktif. Soal ke-71 dan seterusnya otomatis dinonaktifkan berdasarkan urutan.";
                return redirect()->route('admin.questions.index')
                    ->with('warning', $message);
            }
            
            if ($result['total_active'] >= $maxActiveQuestions) {
                $message .= " Maksimal 70 soal aktif telah tercapai. Soal ke-71 dan seterusnya otomatis dinonaktifkan.";
            }

            return redirect()->route('admin.questions.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.import.show')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
