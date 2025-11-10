<?php

namespace App\Exports;

use App\Models\Result;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ResultsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    protected $category;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($category = null, $dateFrom = null, $dateTo = null)
    {
        $this->category = $category;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Result::with(['questionnaire.user'])->latest();

        if ($this->category) {
            $query->where('category', $this->category);
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Responden',
            'Email Responden',
            'Telepon Responden',
            'Nama User',
            'Email User',
            'Total Skor',
            'Persentase (%)',
            'Kategori',
            'Tanggal',
        ];
    }

    /**
     * @param Result $result
     * @return array
     */
    public function map($result): array
    {
        static $index = 0;
        $index++;

        $categoryLabels = [
            'sangat_siap' => 'Sangat Siap',
            'cukup_siap' => 'Cukup Siap',
            'kurang_siap' => 'Kurang Siap',
        ];

        return [
            $index,
            $result->questionnaire->name ?? '-',
            $result->questionnaire->email ?? '-',
            $result->questionnaire->phone ?? '-',
            $result->questionnaire->user->name ?? '-',
            $result->questionnaire->user->email ?? '-',
            $result->total_score,
            number_format($result->percentage, 2),
            $categoryLabels[$result->category] ?? $result->category,
            $result->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Hasil Kuesioner';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E3F2FD']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
