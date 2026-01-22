<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{

    /**
     * Menampilkan halaman manajemen langganan
     */
    public function subscription()
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();
        $currentSubscription = $workshop->activeSubscription;
        $plans = Plan::orderBy('price', 'asc')->get();

        return view('settings.subscription', compact('workshop', 'currentSubscription', 'plans'));
    }

    /**
     * Menampilkan halaman edit profil bengkel
     */
    public function profile()
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();
        return view('settings.profile', compact('workshop'));
    }

    /**
     * Memproses update profil bengkel
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // 1. Validasi Input
        $validated = $request->validate([
            'workshop_name' => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'nullable|string|max:500',
            // Validasi email unik, kecuali untuk bengkel ini sendiri
            'email'         => [
                'required',
                'email',
                'max:255',
                Rule::unique('workshops', 'email')->ignore($workshop->workshop_id, 'workshop_id')
            ],
            // Password opsional (hanya jika diisi)
            'password'      => 'nullable|string|min:8|confirmed',
            // Validasi Foto/Logo
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh bengkel lain.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ]);

        // 2. Handle Password Update
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            // Hapus dari array agar tidak menimpa password lama dengan null/kosong
            unset($validated['password']);
        }

        // 3. Handle Logo Upload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada (dan bukan default)
            if ($workshop->logo && Storage::disk('public')->exists($workshop->logo)) {
                Storage::disk('public')->delete($workshop->logo);
            }

            // Simpan logo baru
            $path = $request->file('logo')->store('workshop-logos', 'public');
            $validated['logo'] = $path;
        }

        // 4. Update Data ke Database
        $workshop->update($validated);

        return back()->with('success', 'Profil bengkel berhasil diperbarui!');
    }
}