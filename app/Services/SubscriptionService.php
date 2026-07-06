<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Workshop;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class SubscriptionService
{
    private function initMidtrans(): void
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function checkout(Workshop $workshop, Plan $plan): array
    {
        $price = (float) $plan->price;
        $isFree = $price <= 0;

        if ($isFree && $workshop->hasUsedFreePlan()) {
            throw new \RuntimeException('Anda sudah pernah menggunakan masa percobaan Gratis. Silakan upgrade ke paket PRO.');
        }

        Log::info('SUBS-CHECKOUT: User checkout', [
            'workshop' => $workshop->workshop_name,
            'plan' => $plan->plan_name,
            'price' => $price,
            'is_free' => $isFree,
        ]);

        try {
            return DB::transaction(function () use ($workshop, $plan, $price, $isFree) {
                $activeSub = Subscription::where('workshop_id', $workshop->workshop_id)
                    ->where('status', 'active')
                    ->where('date_end', '>', now())
                    ->orderBy('date_end', 'desc')
                    ->lockForUpdate()
                    ->first();

                $startDate = now();
                $endDate = now()->addDays($plan->duration_days);

                if ($activeSub) {
                    $daysLeft = now()->diffInDays($activeSub->date_end, false);
                    if ($daysLeft > 0) {
                        $endDate = $endDate->addDays($daysLeft);
                        Log::info("SUBS-CHECKOUT: Menambahkan sisa {$daysLeft} hari ke paket baru.");
                    }
                    $activeSub->update(['status' => 'expired']);
                }

                $initialStatus = $isFree ? 'active' : 'pending';

                $subscription = Subscription::create([
                    'workshop_id'  => $workshop->workshop_id,
                    'plan_id'      => $plan->plan_id,
                    'date_start'   => $startDate,
                    'date_end'     => $endDate,
                    'status'       => $initialStatus,
                    'total_price'  => $price,
                ]);

                if ($isFree) {
                    Log::info("SUBS-CHECKOUT: Paket Gratis ID {$subscription->getKey()} BERHASIL diaktifkan.");
                    return [
                        'is_free' => true,
                        'subscription' => $subscription,
                    ];
                }

                $this->initMidtrans();

                $orderId = 'SUBS-' . $subscription->getKey() . '-' . time();

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
                        ],
                    ],
                ];

                $snapToken = Snap::getSnapToken($params);

                return [
                    'is_free' => false,
                    'subscription' => $subscription,
                    'plan' => $plan,
                    'snapToken' => $snapToken,
                ];
            });
        } catch (Exception $e) {
            Log::error('SUBS-CHECKOUT ERROR: ' . $e->getMessage());
            throw $e;
        }
    }
}
