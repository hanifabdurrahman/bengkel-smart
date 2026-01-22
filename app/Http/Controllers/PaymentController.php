<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function pendingList(Request $request)
    {
        /** @var \App\Models\Workshop $workshop */
        // Ambil service yang:
        // 1. Milik bengkel login
        // 2. Status servis sudah 'selesai' (mekanik sudah beres)
        // 3. Status pembayaran di transaksi masih 'pending'

        $query = Service::with(['customer', 'transaction'])
            ->where('workshop_id', Auth::user()->workshop_id)
            ->where('status', 'selesai')
            ->whereHas('transaction', function ($q) {
                $q->where('status_pembayaran', 'pending');
            });

        // Fitur Search sederhana
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('license_plate', 'LIKE', "%{$search}%");
            });
        }

        $pendingServices = $query->orderBy('waktu_selesai', 'desc')->paginate(10);

        // Menghitung Total Potensi Pendapatan dari antrian kasir ini
        $totalRevenuePending = $pendingServices->getCollection()->sum(function ($service) {
            return $service->transaction->total_akhir;
        });

        return view('payments.pending', compact('pendingServices', 'totalRevenuePending'));
    }
}