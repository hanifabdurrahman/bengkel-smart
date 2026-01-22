<?php

namespace App\Http\Controllers;

use App\Models\SalesDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Service;
use App\Models\Transaction;
use App\Models\Sparepart;

class DashboardController extends Controller
{
    public function index()
    {
        Carbon::setLocale('id');
        /** @var \App\Models\Workshop $workshop */
        $workshop     = Auth::user();

        $today        = Carbon::today();
        $currentYear  = Carbon::now()->year;

        // Langganan aktif
        $activeSub = $workshop->activeSubscription;

        // Hitung sisa hari
        $daysLeft = 0;
        if ($activeSub) {
            $daysLeft = ceil(Carbon::now()->diffInDays($activeSub->date_end, false));
        }

        /* --------------------------------------------------------------------
         | 1. KARTU STATISTIK (Top Row)
         -------------------------------------------------------------------- */

        // Total pendapatan harian
        $dailyRevenue = $workshop->transactions()
            ->whereDate('created_at', $today)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_akhir');

        // Total pelanggan
        $totalCustomers = $workshop->customers()->count();

        // Total service
        $total_service = Service::where('workshop_id', $workshop->workshop_id)->count();

        // Servis selesai hari ini
        $servicesToday = $workshop->transactions()
            ->whereDate('created_at', $today)
            ->where('status_pembayaran', 'lunas')
            ->count();


        /* --------------------------------------------------------------------
         | 2. GRAFIK SERVIS MINGGUAN (Bar Chart)
         -------------------------------------------------------------------- */

        $weeklyLabels = [];
        $weeklyData   = [];

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);

            $weeklyLabels[] = ucfirst($date->locale('id')->dayName);

            $count = $workshop->transactions()
                ->whereDate('created_at', $date)
                ->where('status_pembayaran', 'lunas')
                ->count();

            $weeklyData[] = $count;
        }


        /* --------------------------------------------------------------------
         | 3. GRAFIK SPAREPART (Doughnut Chart)
         -------------------------------------------------------------------- */

        $topSpareparts = SalesDetails::join('spareparts', 'sales_details.sparepart_id', '=', 'spareparts.sparepart_id')
            ->join('transactions', 'sales_details.transaction_id', '=', 'transactions.transaction_id')
            ->where('transactions.workshop_id', $workshop->workshop_id)
            ->selectRaw('spareparts.sparepart_name AS name, SUM(sales_details.jumlah) AS total_qty')
            ->groupBy('spareparts.sparepart_name')
            ->orderByDesc('total_qty')
            ->limit(4)
            ->get();


        $sparepartLabels = $topSpareparts->pluck('name')->toArray();
        $sparepartData   = $topSpareparts->pluck('total_qty')->toArray();

        if (empty($sparepartLabels)) {
            $sparepartLabels = ['Belum ada data'];
            $sparepartData   = [1];
        }


        /* --------------------------------------------------------------------
         | 4. GRAFIK PENDAPATAN TAHUNAN (Line Chart)
         -------------------------------------------------------------------- */

        $months = [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'Mei',
            'Jun',
            'Jul',
            'Agu',
            'Sep',
            'Okt',
            'Nov',
            'Des'
        ];

        $monthlyRevenue = [];

        for ($m = 1; $m <= 12; $m++) {
            $revenue = $workshop->transactions()
                ->whereYear('created_at', $currentYear)
                ->whereMonth('created_at', $m)
                ->where('status_pembayaran', 'lunas')
                ->sum('total_akhir');

            $monthlyRevenue[] = $revenue;
        }

        $totalYearlyRevenue = array_sum($monthlyRevenue);

        // Pendapatan tahun lalu untuk membandingkan growth %
        $lastYearRevenue = $workshop->transactions()
            ->whereYear('created_at', $currentYear - 1)
            ->where('status_pembayaran', 'lunas')
            ->sum('total_akhir');

        $growthPercentage = 0;
        if ($lastYearRevenue > 0) {
            $growthPercentage = (($totalYearlyRevenue - $lastYearRevenue) / $lastYearRevenue) * 100;
        }

        /* --------------------------------------------------------------------
         | Kirim data ke view
         -------------------------------------------------------------------- */

        return view('dashboard.index', compact(
            'workshop',
            'activeSub',
            'daysLeft',
            'totalCustomers',
            'servicesToday',
            'dailyRevenue',
            'weeklyLabels',
            'weeklyData',
            'months',
            'currentYear',
            'monthlyRevenue',
            'totalYearlyRevenue',
            'growthPercentage',
            'topSpareparts',
            'sparepartLabels',
            'sparepartData',
            'total_service'
        ));
    }

    // Tambahkan di DashboardController.php

    public function getServiceTraffic(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();
        $filter = $request->get('filter', 'weekly');

        $labels = [];
        $data = [];

        if ($filter === 'monthly') {
            $startDate = Carbon::now()->subDays(29)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            // Ambil semua data dalam satu query
            $rawData = $workshop->transactions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status_pembayaran', 'lunas')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i)->format('Y-m-d');
                $displayDate = Carbon::parse($date)->format('d M');

                $labels[] = $displayDate;
                $data[] = $rawData[$date] ?? 0; // Ambil dari koleksi atau 0 jika tidak ada
            }
        } else {
            $startDate = Carbon::now()->startOfWeek(Carbon::MONDAY);
            $endDate = Carbon::now()->endOfWeek(Carbon::SUNDAY);

            $rawData = $workshop->transactions()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('status_pembayaran', 'lunas')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->pluck('count', 'date');

            for ($i = 0; $i < 7; $i++) {
                $dateObj = $startDate->copy()->addDays($i);
                $date = $dateObj->format('Y-m-d');

                $labels[] = ucfirst($dateObj->locale('id')->dayName);
                $data[] = $rawData[$date] ?? 0;
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data
        ]);
    }
}