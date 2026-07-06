<?php

namespace App\Services;

use App\Models\SalesDetails;
use App\Models\Service;
use App\Models\Workshop;
use Carbon\Carbon;

class DashboardService
{
    public function getDashboardData(Workshop $workshop): array
    {
        Carbon::setLocale('id');

        $today       = Carbon::today();
        $currentYear = Carbon::now()->year;

        $activeSub = $workshop->activeSubscription;
        $daysLeft  = $activeSub ? ceil(Carbon::now()->diffInDays($activeSub->date_end, false)) : 0;

        $dailyRevenue = $workshop->transactions()
            ->whereDate('created_at', $today)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_akhir');

        $totalCustomers = $workshop->customers()->count();
        $totalService   = Service::where('workshop_id', $workshop->workshop_id)->count();

        $servicesToday = $workshop->transactions()
            ->whereDate('created_at', $today)
            ->where('status_pembayaran', 'lunas')
            ->count();

        $weeklyLabels = [];
        $weeklyData   = [];
        $startOfWeek  = Carbon::now()->startOfWeek(Carbon::MONDAY);

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $weeklyLabels[] = ucfirst($date->locale('id')->dayName);

            $weeklyData[] = $workshop->transactions()
                ->whereDate('created_at', $date)
                ->where('status_pembayaran', 'lunas')
                ->count();
        }

        $topSpareparts = SalesDetails::join('spareparts', 'sales_details.sparepart_id', '=', 'spareparts.sparepart_id')
            ->join('transactions', 'sales_details.transaction_id', '=', 'transactions.transaction_id')
            ->where('transactions.workshop_id', $workshop->workshop_id)
            ->selectRaw('spareparts.sparepart_name AS name, SUM(sales_details.jumlah) AS total_qty')
            ->groupBy('spareparts.sparepart_name')
            ->orderByDesc('total_qty')
            ->limit(4)
            ->get();

        $sparepartLabels = $topSpareparts->pluck('name')->toArray() ?: ['Belum ada data'];
        $sparepartData   = $topSpareparts->pluck('total_qty')->toArray() ?: [1];

        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $monthlyRevenue = [];

        for ($m = 1; $m <= 12; $m++) {
            $monthlyRevenue[] = $workshop->transactions()
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->where('status_pembayaran', 'lunas')
                ->sum('total_akhir');
        }

        $totalYearlyRevenue = array_sum($monthlyRevenue);

        $lastYearRevenue = $workshop->transactions()
            ->whereYear('created_at', $currentYear - 1)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_akhir');

        $growthPercentage = $lastYearRevenue > 0
            ? (($totalYearlyRevenue - $lastYearRevenue) / $lastYearRevenue) * 100
            : 0;

        return [
            'workshop'           => $workshop,
            'activeSub'          => $activeSub,
            'daysLeft'           => $daysLeft,
            'totalCustomers'     => $totalCustomers,
            'servicesToday'      => $servicesToday,
            'dailyRevenue'       => $dailyRevenue,
            'weeklyLabels'       => $weeklyLabels,
            'weeklyData'         => $weeklyData,
            'months'             => $monthNames,
            'currentYear'        => $currentYear,
            'monthlyRevenue'     => $monthlyRevenue,
            'totalYearlyRevenue' => $totalYearlyRevenue,
            'growthPercentage'   => $growthPercentage,
            'sparepartLabels'    => $sparepartLabels,
            'sparepartData'      => $sparepartData,
            'total_service'      => $totalService,
        ];
    }

    public function getServiceTrafficData(Workshop $workshop, string $filter = 'weekly'): array
    {
        $labels = [];
        $data   = [];

        if ($filter === 'monthly') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate   = Carbon::now()->endOfDay();

            $rawData = $workshop->transactions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status_pembayaran', 'lunas')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            for ($i = 29; $i >= 0; $i--) {
                $date       = Carbon::now()->subDays($i)->format('Y-m-d');
                $labels[]   = Carbon::parse($date)->format('d M');
                $data[]     = $rawData[$date] ?? 0;
            }
        } else {
            $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endDate   = Carbon::now()->endOfWeek(Carbon::SUNDAY);

            $rawData = $workshop->transactions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status_pembayaran', 'lunas')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            for ($i = 0; $i < 7; $i++) {
                $dateObj   = $startDate->copy()->addDays($i);
                $date      = $dateObj->format('Y-m-d');
                $labels[]  = ucfirst($dateObj->locale('id')->dayName);
                $data[]    = $rawData[$date] ?? 0;
            }
        }

        return compact('labels', 'data');
    }
}
