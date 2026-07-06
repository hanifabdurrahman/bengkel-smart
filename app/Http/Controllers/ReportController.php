<?php

namespace App\Http\Controllers;

use App\Exports\FinancialReportExport;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    public function index(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $data = $this->reportService->getReportData($workshop, $startDate, $endDate);

        return view('reports.index', $data);
    }

    public function export(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        if (!$workshop->is_premium) {
            return back()->with('error', 'Fitur Export Excel hanya untuk akun Premium.');
        }

        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        $data = $this->reportService->getExportData($workshop, $startDate, $endDate);

        $namaFile = 'Laporan_Keuangan_' . $startDate . '_sd_' . $endDate . '.xlsx';

        return Excel::download(new FinancialReportExport($data), $namaFile);
    }
}
