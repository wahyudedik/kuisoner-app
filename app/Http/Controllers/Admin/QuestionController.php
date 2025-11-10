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

        return view('admin.questions.create', compact('categories', 'maxOrder'));
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

        Question::create([
            'question_text' => $validated['question_text'],
            'category' => $validated['category'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Pertanyaan berhasil ditambahkan.');
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

        return view('admin.questions.edit', compact('question', 'categories'));
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

        $question->update([
            'question_text' => $validated['question_text'],
            'category' => $validated['category'],
            'order' => $validated['order'],
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.questions.index')
            ->with('success', 'Pertanyaan berhasil diperbarui.');
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
            Excel::import(new QuestionsImport, $request->file('file'));

            return redirect()->route('admin.questions.index')
                ->with('success', 'Pertanyaan berhasil diimpor dari Excel.');
        } catch (\Exception $e) {
            return redirect()->route('admin.questions.import.show')
                ->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }
}
