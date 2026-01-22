<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $data;

    // 1. Constructor untuk menerima data dari Controller
    public function __construct($data)
    {
        $this->data = $data;
    }

    // 2. Load View Blade untuk di-render jadi Excel
    public function view(): View
    {
        // Pastikan file resources/views/reports/excel.blade.php sudah dibuat
        return view('reports.excel', $this->data);
    }

    // 3. Styling Tambahan (Opsional)
    public function styles(Worksheet $sheet)
    {
        return [
            // Membuat Baris 1 (Header) menjadi Bold
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }
}
