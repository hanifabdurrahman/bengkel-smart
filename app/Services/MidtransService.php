<?php

namespace App\Services;

use App\Models\Subscription;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function processNotification(): array
    {
        $notif = new Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $orderId = $notif->order_id;
        $fraud = $notif->fraud_status;

        Log::info("Midtrans Hit: Order ID [{$orderId}] | Status [{$transaction}]");

        $parts = explode('-', $orderId);

        if (count($parts) < 2) {
            Log::error("Format Order ID salah: {$orderId}.");
            throw new \RuntimeException('Invalid Order ID format');
        }

        $subscriptionId = $parts[1];

        if (empty($subscriptionId) || !is_numeric($subscriptionId)) {
            Log::error("ID Subscription tidak valid: '{$subscriptionId}'");
            throw new \RuntimeException('Invalid Subscription ID');
        }

        $subscription = Subscription::find($subscriptionId);

        if (!$subscription) {
            Log::error("Subscription dengan ID {$subscriptionId} tidak ditemukan.");
            throw new \RuntimeException('Subscription not found');
        }

        $updateData = [];

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                $updateData['status'] = ($fraud == 'challenge') ? 'pending' : 'active';
            }
        } elseif ($transaction == 'settlement') {
            $updateData['status'] = 'active';
            Log::info("Subscription {$subscriptionId} LUNAS & AKTIF.");
        } elseif ($transaction == 'pending') {
            $updateData['status'] = 'pending';
        } elseif (in_array($transaction, ['deny', 'expire', 'cancel'])) {
            $updateData['status'] = 'inactive';
            Log::info("Subscription {$subscriptionId} GAGAL/BATAL.");
        }

        if (!empty($updateData)) {
            $subscription->update($updateData);
        }

        return ['message' => 'Notification processed'];
    }
}
