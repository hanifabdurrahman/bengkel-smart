<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // HALAMAN PEMBAYARAN (INVOICE PREVIEW)
    public function payment($id)
    {
        // Cari transaksi berdasarkan ID Servis atau ID Transaksi
        // Asumsi $id disini adalah transaction_id
        $transaction = Transaction::with(['customer', 'services', 'salesDetails.sparepart'])
            ->where('workshop_id', Auth::user()->workshop_id)
            ->findOrFail($id);

        return view('transactions.payment', compact('transaction'));
    }

    // PROSES BAYAR (LUNAS)
    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'diskon' => 'numeric|min:0',
            'bayar'  => 'required|numeric|min:0', // Uang yang diterima
        ]);

        $transaction = Transaction::findOrFail($id);

        // Hitung ulang total akhir jika ada diskon baru
        $totalPart = $transaction->total_sparepart;
        $totalJasa = $transaction->total_jasa;
        $diskon    = $request->diskon ?? 0;
        $totalAkhir = ($totalPart + $totalJasa) - $diskon;

        // Validasi pembayaran
        if ($request->bayar < $totalAkhir) {
            return back()->with('error', 'Uang pembayaran kurang!');
        }

        // Update Transaksi
        $transaction->update([
            'diskon'            => $diskon,
            'total_akhir'       => $totalAkhir,
            'status_pembayaran' => 'lunas'
        ]);

        // Update Status Servis jadi Selesai (jika belum)
        if ($transaction->service) {
            $transaction->service->update([
                'status'        => 'selesai',
                'waktu_selesai' => now()
            ]);
        }

        return redirect()->route('transactions.invoice', $transaction->transaction_id)
            ->with('success', 'Pembayaran Berhasil!');
    }

    // CETAK NOTA
    public function invoice($id)
    {
        $transaction = Transaction::with(['customer', 'services', 'salesDetails.sparepart'])
            ->where('workshop_id', Auth::user()->workshop_id)
            ->findOrFail($id);

        return view('transactions.invoice', compact('transaction'));
    }
}