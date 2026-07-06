<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $workshop = Workshop::create([
            'workshop_name' => $request->workshop_name,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'address'       => $request->address,
            'password'      => Hash::make($request->password),
        ]);

        Auth::login($workshop);

        return redirect()->route('plans.page')
            ->with('info', 'Registrasi berhasil! Silakan pilih paket untuk melanjutkan.');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $targetUrl = $request->input('redirect_to');

            if ($targetUrl && str_contains($targetUrl, '/subscription/checkout')) {
                return redirect($targetUrl);
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
