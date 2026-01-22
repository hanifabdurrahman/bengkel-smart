<?php

namespace App\Http\Controllers;

use App\Models\SalesDetails;
use App\Models\Sparepart;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServicePartController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'sparepart_id'   => 'required|exists:spareparts,sparepart_id',
            'qty'            => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $sparepart = Sparepart::findOrFail($request->sparepart_id);

            // 1. Cek Stok
            if ($sparepart->stock_quantity < $request->qty) {
                return back()->with('error', 'Stok kurang! Sisa: ' . $sparepart->stock_quantity);
            }

            // 2. Kurangi Stok
            $sparepart->decrement('stock_quantity', $request->qty);

            // 3. Masukkan ke SalesDetails
            $subTotal = $sparepart->selling_price * $request->qty;

            SalesDetails::create([
                'transaction_id' => $request->transaction_id,
                'sparepart_id'   => $sparepart->sparepart_id,
                'jumlah'         => $request->qty,
                'harga_satuan'   => $sparepart->selling_price,
                'current_buying_price' => $sparepart->buying_price,
                'sub_total'      => $subTotal
            ]);

            // 4. Update Total di Tabel Transaction
            // Kita panggil fungsi helper di bawah
            $this->updateTransactionTotal($request->transaction_id);

            DB::commit();
            return back()->with('success', 'Sparepart berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }

    // HAPUS SPAREPART DARI SERVIS
    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            // Cari data di model SalesDetails
            $detail = SalesDetails::findOrFail($id);
            $transactionId = $detail->transaction_id;

            // Balikin Stok ke Gudang
            $sparepart = Sparepart::findOrFail($detail->sparepart_id);
            $sparepart->increment('stock_quantity', $detail->jumlah);

            // Hapus Data
            $detail->delete();

            // Hitung Ulang Total
            $this->updateTransactionTotal($transactionId);

            DB::commit();
            return back()->with('success', 'Item dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal hapus: ' . $e->getMessage());
        }
    }

    public function updateQty(Request $request, $id)
    {
        $request->validate([
            'qty' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            // 1. Ambil Data Detail Transaksi
            $detail = SalesDetails::findOrFail($id);
            $transactionId = $detail->transaction_id;

            // 2. Ambil Data Sparepart
            $sparepart = Sparepart::findOrFail($detail->sparepart_id);

            // 3. Hitung Selisih (Qty Baru - Qty Lama)
            $oldQty = $detail->jumlah;
            $newQty = $request->qty;
            $diff = $newQty - $oldQty;

            // Jika Qty Bertambah (Misal 1 jadi 3, selisih +2) -> Cek & Kurangi Stok Gudang
            if ($diff > 0) {
                if ($sparepart->stock_quantity < $diff) {
                    return back()->with('error', 'Stok tidak cukup untuk penambahan!');
                }
                $sparepart->decrement('stock_quantity', $diff);
            }

            // Jika Qty Berkurang (Misal 3 jadi 1, selisih -2) -> Kembalikan Stok ke Gudang
            if ($diff < 0) {
                $sparepart->increment('stock_quantity', abs($diff)); // abs() biar positif
            }

            // 4. Update Detail Transaksi
            $subTotalBaru = $sparepart->selling_price * $newQty;
            $detail->update([
                'jumlah' => $newQty,
                'sub_total' => $subTotalBaru
            ]);

            // 5. Update Total Transaksi (Panggil fungsi helper yang sudah ada)
            $this->updateTransactionTotal($transactionId);

            DB::commit();
            return back()->with('success', 'Qty berhasil diupdate.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function searchAjax(Request $request)
    {
        $query = $request->get('q');

        // Ambil ID bengkel dari user yang login
        $workshopId = Auth::user()->workshop_id;

        $spareparts = Sparepart::query()
            // 1. Filter Wajib: Hanya milik bengkel ini
            ->where('workshop_id', $workshopId)

            // 2. Filter Pencarian (Harus dibungkus dalam function agar logika AND & OR tidak bentrok)
            ->where(function ($q) use ($query) {
                $q->where('sparepart_name', 'LIKE', "%{$query}%")
                    ->orWhere('sparepart_code', 'LIKE', "%{$query}%");
            })

            ->limit(10) // Batasi hasil
            ->get([
                'sparepart_id',
                'sparepart_code',
                'sparepart_name',
                'stock_quantity',
                'selling_price'
            ]);

        return response()->json($spareparts);
    }

    // --- FUNGSI PENTING UNTUK HITUNG TOTAL ---
    private function updateTransactionTotal($id)
    {
        $transaction = Transaction::with(['salesDetails', 'services'])->findOrFail($id);

        $totalPart = $transaction->salesDetails->sum('sub_total');
        $totalJasa = $transaction->services ? $transaction->services->biaya_jasa : 0;
        $transaction->update([
            'total_sparepart' => $totalPart,
            'total_jasa'      => $totalJasa,
            'total_akhir'     => ($totalPart + $totalJasa) - $transaction->diskon
        ]);
    }
}
