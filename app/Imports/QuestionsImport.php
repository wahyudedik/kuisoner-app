<?php

namespace App\Imports;

use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class QuestionsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Map category labels to category keys
        $categoryMap = [
            'keuangan' => 'keuangan',
            'emosional' => 'emosional',
            'pendidikan' => 'pendidikan',
            'pengasuhan anak' => 'pengasuhan_anak',
            'pengasuhan_anak' => 'pengasuhan_anak',
            'komunikasi & konflik' => 'komunikasi',
            'komunikasi' => 'komunikasi',
            'sosial & lingkungan' => 'sosial',
            'sosial' => 'sosial',
            'tanggung jawab' => 'tanggung_jawab',
            'tanggung_jawab' => 'tanggung_jawab',
        ];

        $category = null;
        if (isset($row['kategori'])) {
            $categoryInput = strtolower(trim($row['kategori']));
            $category = $categoryMap[$categoryInput] ?? $categoryInput;
        }

        // Get max order if order not provided
        $order = isset($row['urutan']) && $row['urutan'] ? (int)$row['urutan'] : (Question::max('order') ?? 0) + 1;

        // Determine is_active
        $isActive = true;
        if (isset($row['status_aktif'])) {
            $status = strtolower(trim($row['status_aktif']));
            $isActive = in_array($status, ['aktif', '1', 'true', 'yes', 'y']);
        }

        return new Question([
            'question_text' => $row['pertanyaan'] ?? $row['question_text'] ?? '',
            'category' => $category ?? 'keuangan',
            'order' => $order,
            'is_active' => $isActive,
        ]);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'pertanyaan' => 'required|string|max:1000',
            'kategori' => 'required|string',
            'urutan' => 'nullable|integer|min:1',
            'status_aktif' => 'nullable|string',
        ];
    }
}
