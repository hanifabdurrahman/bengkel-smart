<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Cache::remember('plans_list_public', now()->addMinutes(10), function () {
            return Plan::orderBy('price', 'asc')->get();
        });

        $hasUsedFreePlan = false;
        if (Auth::check()) {
            /** @var \App\Models\Workshop $user */
            $user = Auth::user();
            $hasUsedFreePlan = $user->hasUsedFreePlan();
        }

        return view('plans.index', compact('plans', 'hasUsedFreePlan'));
    }
}
