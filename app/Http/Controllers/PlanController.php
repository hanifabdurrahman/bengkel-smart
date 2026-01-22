<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PlanController extends Controller
{
    /**
     * Menampilkan halaman daftar harga (Pricing Page)
     * Route: GET /plans atau /pricing
     */
    public function index()
    {
        // Ambil data paket, cache selama 10 menit agar ringan
        // Urutkan berdasarkan harga termurah
        $plans = Cache::remember('plans_list_public', now()->addMinutes(10), function () {
            return Plan::orderBy('price', 'asc')->get();
        });

        if (Auth::check()) {
            // Kita ambil user yang login, lalu panggil method yang baru kita buat di Model
            /** @var \App\Models\Workshop $user */
            $user = Auth::user();
            $hasUsedFreePlan = $user->hasUsedFreePlan();
        }


        return view('plans.index', compact('plans', 'hasUsedFreePlan'));
    }
}
