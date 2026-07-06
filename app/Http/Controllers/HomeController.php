<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $plans = Cache::remember('plans_public_list', now()->addMinutes(10), function () {
            return Plan::orderBy('price', 'asc')->get();
        });

        return view('landingPage', compact('plans'));
    }

    public function register_page()
    {
        return view('auth.main', ['action' => 'register']);
    }

    public function login_page()
    {
        return view('auth.main', ['action' => 'login']);
    }
}
