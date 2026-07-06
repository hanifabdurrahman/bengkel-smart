<?php

namespace App\Services;

use App\Models\Workshop;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class ReportService
{
    private function calculateModalAndProfit($transactions): array
    {
        $totalModal = 0;
        $profitChart = [];

        foreach ($transactions as $trx) {
            $trxModal = 0;

            foreach ($trx->salesDetails as $detail) {
                $modalPerItem = $detail->current_buying_price > 0
                    ? $detail->current_buying_price
                    : ($detail->sparepart->buying_price ?? 0);

                $trxModal += ($modalPerItem * $detail->jumlah);
            }

            $trx->modal_transaksi = $trxModal;
            $trx->profit_transaksi = $trx->total_akhir - $trxModal;
            $totalModal += $trxModal;

            $dateKey = $trx->created_at->format('Y-m-d');
            $profitChart[$dateKey] = ($profitChart[$dateKey] ?? 0) + $trx->profit_transaksi;
        }

        return [$totalModal, $profitChart];
    }

    public function getReportData(Workshop $workshop, string $startDate, string $endDate): array
    {
        $startDate = Carbon::parse($startDate)->toDateString();
        $endDate = Carbon::parse($endDate)->toDateString();

        $isPremium = $workshop->is_premium;

        $transactions = $workshop->transactions()
            ->with(['salesDetails.sparepart', 'services', 'customer'])
            ->where('status_pembayaran', 'lunas')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        $totalRevenue = $transactions->sum('total_akhir');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;

        $totalModal = 0;
        $netProfit = 0;
        $profitChart = [];

        if ($isPremium) {
            [$totalModal, $profitChart] = $this->calculateModalAndProfit($transactions);
            $netProfit = $totalRevenue - $totalModal;
        }

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

        $chartLabels = [];
        $revenueValues = [];
        $profitValues = [];

        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $formattedDate = $date->format('Y-m-d');
            $chartLabels[] = $date->format('d M');
            $revenueValues[] = $chartDataRaw[$formattedDate] ?? 0;

            if ($isPremium) {
                $profitValues[] = $profitChart[$formattedDate] ?? 0;
            }
        }

        return compact(
            'transactions', 'totalRevenue', 'totalTransactions',
            'averageTransaction', 'startDate', 'endDate',
            'chartLabels', 'revenueValues', 'profitValues',
            'isPremium', 'totalModal', 'netProfit'
        );
    }

    public function getExportData(Workshop $workshop, string $startDate, string $endDate): array
    {
        $startDate = Carbon::parse($startDate)->toDateString();
        $endDate = Carbon::parse($endDate)->toDateString();

        $transactions = $workshop->transactions()
            ->with(['salesDetails.sparepart', 'services', 'customer'])
            ->where('status_pembayaran', 'lunas')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->oldest()
            ->get();

        $totalRevenue = 0;
        $totalModal = 0;

        foreach ($transactions as $trx) {
            $trxModal = 0;

            foreach ($trx->salesDetails as $detail) {
                $modalPerItem = $detail->current_buying_price > 0
                    ? $detail->current_buying_price
                    : ($detail->sparepart->buying_price ?? 0);
                $trxModal += ($modalPerItem * $detail->jumlah);
            }

            if ($trx->services) {
                $trxModal += $trx->services->biaya_jasa_modal ?? 0;
            }

            $trx->modal_transaksi = $trxModal;
            $trx->profit_transaksi = $trx->total_akhir - $trxModal;

            $totalRevenue += $trx->total_akhir;
            $totalModal += $trxModal;
        }

        $netProfit = $totalRevenue - $totalModal;

        return compact('transactions', 'startDate', 'endDate', 'totalRevenue', 'totalModal', 'netProfit', 'workshop');
    }
}
