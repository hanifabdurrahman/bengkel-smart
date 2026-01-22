<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkshopIsSubscribed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Pastikan user sudah login
        if (!Auth::check()) {
            return redirect()->route('login.page');
        }

        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // 2. Cek apakah punya langganan aktif menggunakan fungsi di Model tadi
        if (! $workshop->hasActiveSubscription()) {

            // PENTING: Jangan redirect jika user sudah berada di halaman pricing/checkout
            // agar tidak terjadi infinite loop (Redirect Loop)
            if ($request->routeIs('plans.page') || $request->routeIs('subscription.*')) {
                return $next($request);
            }

            // Redirect ke halaman pilih paket
            return redirect()->route('plans.page')
                ->with('error', 'Akun Anda aktif, tetapi belum memiliki paket langganan. Silakan pilih paket.');
        }
        return $next($request);
    }
}
