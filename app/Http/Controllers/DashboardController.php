<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    public function index()
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        $data = $this->dashboardService->getDashboardData($workshop);

        return view('dashboard.index', $data);
    }

    public function getServiceTraffic(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        $result = $this->dashboardService->getServiceTrafficData(
            $workshop,
            $request->get('filter', 'weekly')
        );

        return response()->json($result);
    }
}
