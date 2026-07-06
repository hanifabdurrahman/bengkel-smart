<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function __construct(
        private SubscriptionService $subscriptionService
    ) {}

    public function checkout(Request $request, Plan $plan)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        try {
            $result = $this->subscriptionService->checkout($workshop, $plan);
        } catch (\RuntimeException $e) {
            return redirect()->route('plans.page')
                ->with('error', $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }

        if ($result['is_free']) {
            return redirect()->route('dashboard')
                ->with('success', "Paket {$plan->plan_name} berhasil diaktifkan! Selamat bekerja.");
        }

        return view('subscription.payment', [
            'snapToken'    => $result['snapToken'],
            'plan'         => $result['plan'],
            'subscription' => $result['subscription'],
        ]);
    }
}
