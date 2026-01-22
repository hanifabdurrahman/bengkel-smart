<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Exports\FinancialReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // 1. Cek Paket Langganan
        // Jika tidak ada relasi, anggap Free. Jika ada, cek namanya.
        $activePlan = $workshop->subscription ? $workshop->subscription->plan->plan_name : 'FREE';

        $isPremium = $workshop->is_premium;

        // 2. Filter Tanggal
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 3. Query Transaksi (Hanya yang LUNAS)
        $query = $workshop->transactions()
            ->with(['salesDetails.sparepart', 'services', 'customer'])
            ->where('status_pembayaran', 'lunas')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        // Urutkan dari yang terbaru
        $transactions = $query->latest()->get();

        // 4. Hitung Ringkasan Dasar (Untuk Semua User)
        $totalRevenue       = $transactions->sum('total_akhir');
        $totalTransactions  = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        // 5. Hitung Detail Keuangan (KHUSUS PREMIUM)
        $totalModal  = 0;
        $netProfit   = 0;
        $profitChart = []; // Data akumulasi profit harian

        if ($isPremium) {
            foreach ($transactions as $trx) {
                // Hitung Modal Sparepart per Transaksi
                $trxModal = 0;

                foreach ($trx->salesDetails as $detail) {
                    // --- LOGIKA SNAPSHOT MODAL ---
                    // 1. Cek apakah ada 'current_buying_price' (Harga saat transaksi terjadi)?
                    // 2. Jika ada (> 0), gunakan itu.
                    // 3. Jika 0 (transaksi lama sebelum update sistem), fallback ke harga master saat ini.

                    $modalPerItem = $detail->current_buying_price > 0
                        ? $detail->current_buying_price
                        : ($detail->sparepart->buying_price ?? 0);

                    $trxModal += ($modalPerItem * $detail->jumlah);
                }

                // Simpan data modal ke object transaksi (agar bisa ditampilkan di tabel view)
                $trx->modal_transaksi = $trxModal;
                $trx->profit_transaksi = $trx->total_akhir - $trxModal;

                // Akumulasi Total
                $totalModal += $trxModal;

                // Grouping Profit Harian untuk Chart
                $dateKey = $trx->created_at->format('Y-m-d');
                if (!isset($profitChart[$dateKey])) {
                    $profitChart[$dateKey] = 0;
                }
                $profitChart[$dateKey] += $trx->profit_transaksi;
            }

            // Laba Bersih = Omset - Total Modal Barang
            $netProfit = $totalRevenue - $totalModal;
        }

        // 6. Siapkan Data Chart (Revenue & Profit)
        // Ambil data omset harian langsung dari DB agar lebih cepat
        $chartDataRaw = $workshop->transactions()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_akhir) as total')
            )
            ->where('status_pembayaran', 'lunas')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels    = [];
        $revenueValues  = [];
        $profitValues   = []; // Array untuk grafik garis hijau (Profit)

        $period = \Carbon\CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');

            // Revenue (Omset)
            $revenueValues[] = $chartDataRaw[$formattedDate] ?? 0;

            // Profit (Hanya jika Premium)
            if ($isPremium) {
                $profitValues[] = $profitChart[$formattedDate] ?? 0;
            }
        }

        return view('reports.index', compact(
            'transactions',
            'totalRevenue',
            'totalTransactions',
            'averageTransaction',
            'startDate',
            'endDate',
            'chartLabels',
            'revenueValues',
            'profitValues', // Data garis profit
            'isPremium',    // Penentu tampilan UI
            'totalModal',
            'netProfit'
        ));
    }

    // Method Baru: EXPORT EXCEL
    public function export(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // 1. Cek Security (Premium Only)
        if (!$workshop->is_premium) {
            return back()->with('error', 'Fitur Export Excel hanya untuk akun Premium.');
        }

        // 2. Filter Tanggal (Sama seperti index)
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // 3. Query Data
        $query = $workshop->transactions()
            ->with(['salesDetails.sparepart', 'services', 'customer'])
            ->where('status_pembayaran', 'lunas')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $transactions = $query->oldest()->get(); // Oldest agar urut tanggal dari awal

        // 4. Hitung Modal & Profit (Logic Snapshot)
        $totalRevenue = 0;
        $totalModal   = 0;
        $netProfit    = 0;

        foreach ($transactions as $trx) {
            $trxModal = 0;

            // Hitung Modal Sparepart
            foreach ($trx->salesDetails as $detail) {
                $modalPerItem = $detail->current_buying_price > 0
                    ? $detail->current_buying_price
                    : ($detail->sparepart->buying_price ?? 0);
                $trxModal += ($modalPerItem * $detail->jumlah);
            }

            // Hitung Modal Jasa
            if ($trx->services) {
                $trxModal += $trx->services->biaya_jasa_modal;
            }

            // Simpan data kalkulasi ke object trx (agar bisa dipanggil di view excel)
            $trx->modal_transaksi = $trxModal;
            $trx->profit_transaksi = $trx->total_akhir - $trxModal;

            // Akumulasi Total
            $totalRevenue += $trx->total_akhir;
            $totalModal   += $trxModal;
        }

        $netProfit = $totalRevenue - $totalModal;

        // 5. Siapkan Data Array untuk dikirim ke Export Class
        $data = compact('transactions', 'startDate', 'endDate', 'totalRevenue', 'totalModal', 'netProfit', 'workshop');

        // 6. Download File
        $namaFile = 'Laporan_Keuangan_' . $startDate . '_sd_' . $endDate . '.xlsx';
        return Excel::download(new FinancialReportExport($data), $namaFile);
    }
}