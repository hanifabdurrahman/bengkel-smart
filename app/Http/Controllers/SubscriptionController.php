<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Midtrans\Config;
use Midtrans\Snap;

class SubscriptionController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function checkout(Request $request, Plan $plan)
    {
        /** @var \App\Models\Workshop $workshop */
        $workshop = Auth::user();

        // Pastikan harga dianggap angka (float) agar perbandingan akurat
        $price = (float) $plan->price;
        $isFree = $price <= 0;

        // --- LOGIKA BARU: CEK VALIDASI FREE PLAN ---
        if ($isFree && $workshop->hasUsedFreePlan()) {
            return redirect()->route('plans.index')
                ->with('error', 'Anda sudah pernah menggunakan masa percobaan Gratis. Silakan upgrade ke paket PRO.');
        }

        Log::info('SUBS-CHECKOUT: User checkout', [
            'workshop' => $workshop->workshop_name,
            'plan' => $plan->plan_name,
            'price' => $price,
            'is_free' => $isFree
        ]);

        try {
            DB::beginTransaction();

            // 1. Cek Langganan Terakhir yang Aktif
            // Kita gunakan lockForUpdate untuk mencegah race condition
            $activeSub = Subscription::where('workshop_id', $workshop->workshop_id)
                ->where('status', 'active')
                ->where('date_end', '>', now())
                ->orderBy('date_end', 'desc')
                ->lockForUpdate()
                ->first();

            // 2. Hitung Tanggal Mulai & Selesai
            $startDate = now();
            $endDate = now()->addDays($plan->duration_days);

            // LOGIKA PERPANJANGAN (CARRY OVER)
            if ($activeSub) {
                // Hitung sisa hari dari paket lama
                $daysLeft = now()->diffInDays($activeSub->date_end, false);

                if ($daysLeft > 0) {
                    $endDate = $endDate->addDays($daysLeft);
                    Log::info("SUBS-CHECKOUT: Menambahkan sisa {$daysLeft} hari ke paket baru.");
                }

                $activeSub->update(['status' => 'expired']);
            }

            // 3. Tentukan Status Awal
            // Jika Gratis -> LANGSUNG 'active'. Jika Bayar -> 'pending'.
            $initialStatus = $isFree ? 'active' : 'pending';

            // 4. Simpan ke Database
            $subscription = Subscription::create([
                'workshop_id'  => $workshop->workshop_id,
                'plan_id'      => $plan->plan_id,
                'date_start'   => $startDate,
                'date_end'     => $endDate,
                'status'       => $initialStatus,
                'total_price'  => $price,
            ]);

            DB::commit();

            // ====================================================
            // SKENARIO 1: PAKET GRATIS (LANGSUNG AKTIF)
            // ====================================================
            if ($isFree) {
                Log::info("SUBS-CHECKOUT: Paket Gratis ID {$subscription->getKey()} BERHASIL diaktifkan.");

                return redirect()->route('dashboard')
                    ->with('success', "Paket {$plan->plan_name} berhasil diaktifkan! Selamat bekerja.");
            }

            // ====================================================
            // SKENARIO 2: PAKET BERBAYAR (MIDTRANS)
            // ====================================================

            // Generate Order ID
            $orderId = 'SUBS-' . $subscription->getKey() . '-' . time();

            // Parameter Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $price,
                ],
                'customer_details' => [
                    'first_name' => substr($workshop->workshop_name, 0, 20),
                    'email' => $workshop->email,
                    'phone' => $workshop->phone_number,
                ],
                'item_details' => [
                    [
                        'id' => $plan->plan_id,
                        'price' => (int) $price,
                        'quantity' => 1,
                        'name' => substr($plan->plan_name, 0, 50),
                    ]
                ]
            ];

            $snapToken = Snap::getSnapToken($params);

            return view('subscription.payment', compact('snapToken', 'plan', 'subscription'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('SUBS-CHECKOUT ERROR: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}
