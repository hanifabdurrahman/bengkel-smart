<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menggunakan Cache agar query tidak dijalankan setiap kali halaman dibuka
        $plans = Cache::remember('plans_public_list', now()->addMinutes(10), function () {
            return Plan::query()
                ->orderBy('price', 'asc') // PENTING: Urutkan harga dari termurah
                ->get();
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

    // public function plans_page()
    // {
    //     $plans = Cache::remember('plans_public_list', now()->addMinutes(10), function () {
    //         return Plan::query()
    //             ->orderBy('price', 'asc')
    //             ->get();
    //     });
    //     return view('plans.index', compact('plans'));
    // }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
