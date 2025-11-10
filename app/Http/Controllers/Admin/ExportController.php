<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\ResultsExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export results to Excel
     */
    public function exportExcel(Request $request)
    {
        $category = $request->get('category');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        
        $filename = 'hasil-kuesioner-' . date('Y-m-d-His') . '.xlsx';
        
        return Excel::download(
            new ResultsExport($category, $dateFrom, $dateTo),
            $filename
        );
    }
}

