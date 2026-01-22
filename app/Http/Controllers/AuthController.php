<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'workshop_name' => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:workshops,email',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'required|string',
            'password'      => 'required|string|min:8|confirmed',
        ]);

        // 2. Buat Data Workshop
        $workshop = Workshop::create([
            'workshop_name' => $request->workshop_name,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'address'       => $request->address,
            'password'      => Hash::make($request->password),
        ]);

        // 3. Langsung Login setelah daftar
        Auth::login($workshop);

        return redirect()->route('plans.page')
            ->with('info', 'Registrasi berhasil! Silakan pilih paket untuk melanjutkan.');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $targetUrl = $request->input('redirect_to');

            if ($targetUrl && str_contains($targetUrl, '/subscription/checkout')) {
                return redirect($targetUrl);
            }
            return redirect()->intended('dashboard');
        }

        // 3. Jika Gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email', 'remember'));
    }

    // ================= LOGOUT =================

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
