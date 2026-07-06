<?php

namespace App\Http\Controllers;

use App\Http\Requests\Settings\UpdateProfileRequest;
use App\Models\Plan;
use App\Services\SettingsService;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct(
        private SettingsService $settingsService
    ) {}

    public function subscription()
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();
        $currentSubscription = $workshop->activeSubscription;
        $plans = Plan::orderBy('price', 'asc')->get();

        return view('settings.subscription', compact('workshop', 'currentSubscription', 'plans'));
    }

    public function profile()
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        return view('settings.profile', compact('workshop'));
    }

    public function updateProfile(UpdateProfileRequest $request)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        $this->settingsService->updateProfile($workshop, $request->validated());

        return back()->with('success', 'Profil bengkel berhasil diperbarui!');
    }
}
