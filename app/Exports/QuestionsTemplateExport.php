<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class QuestionsTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Contoh data untuk template
        return [
            [
                'Saya memiliki sumber penghasilan tetap atau rencana jelas untuk memenuhi kebutuhan keluarga.',
                'Keuangan',
                1,
                'Aktif'
            ],
            [
                'Saya mampu mengatur pengeluaran dan membuat rencana keuangan bulanan.',
                'Keuangan',
                2,
                'Aktif'
            ],
            [
                'Saya mampu mengendalikan emosi ketika menghadapi masalah dengan pasangan.',
                'Emosional',
                1,
                'Aktif'
            ],
        ];
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Pertanyaan',
            'Kategori',
            'Urutan',
            'Status Aktif',
        ];
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
