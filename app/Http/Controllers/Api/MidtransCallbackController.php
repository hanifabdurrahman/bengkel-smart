<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class MidtransCallbackController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function handle(Request $request)
    {
        try {
            $notif = new Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $orderId = $notif->order_id;
            $fraud = $notif->fraud_status;

            // 1. LOG PENTING: Cek bentuk asli Order ID yang masuk
            Log::info("Midtrans Hit: Order ID [{$orderId}] | Status [{$transaction}]");

            // 2. PARSING ORDER ID
            // Format yang diharapkan: SUBS-{id}-{timestamp} (Contoh: SUBS-15-17382323)
            $parts = explode('-', $orderId);

            // Validasi format (Minimal ada 2 bagian: Prefix dan ID)
            if (count($parts) < 2) {
                Log::error("Format Order ID salah: {$orderId}. ID tidak dapat diambil.");
                return response()->json(['message' => 'Invalid Order ID format'], 400);
            }

            // Ambil ID (Index ke-1)
            $subscriptionId = $parts[1];

            // Cek apakah ID kosong atau bukan angka
            if (empty($subscriptionId) || !is_numeric($subscriptionId)) {
                Log::error("ID Subscription tidak valid (Kosong/Bukan Angka): '{$subscriptionId}'");
                return response()->json(['message' => 'Invalid Subscription ID'], 400);
            }

            // 3. CARI DI DATABASE
            // Karena di model sudah set primaryKey = 'subscription_id', find() otomatis cari kolom itu.
            $subscription = Subscription::find($subscriptionId);

            if (!$subscription) {
                Log::error("Subscription dengan ID {$subscriptionId} tidak ditemukan di database.");
                return response()->json(['message' => 'Subscription not found'], 404);
            }

            // 4. UPDATE STATUS
            // Gunakan variabel $updateData agar kode lebih rapi
            $updateData = [];

            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    $updateData['status'] = ($fraud == 'challenge') ? 'pending' : 'active';
                }
            } else if ($transaction == 'settlement') {
                $updateData['status'] = 'active';

                // Jika ingin update tanggal otomatis saat lunas
                // $updateData['date_end'] = now()->addDays($subscription->plan->duration_days);

                Log::info("Subscription {$subscriptionId} LUNAS & AKTIF.");
            } else if ($transaction == 'pending') {
                $updateData['status'] = 'pending';
            } else if (in_array($transaction, ['deny', 'expire', 'cancel'])) {
                $updateData['status'] = 'inactive';
                Log::info("Subscription {$subscriptionId} GAGAL/BATAL.");
            }

            if (!empty($updateData)) {
                $subscription->update($updateData);
            }

            return response()->json(['message' => 'Notification processed']);
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}
