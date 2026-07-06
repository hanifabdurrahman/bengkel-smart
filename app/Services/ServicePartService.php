<?php

namespace App\Services;

use App\Models\SalesDetails;
use App\Models\Sparepart;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class ServicePartService
{
    private function updateTransactionTotal(int $transactionId): void
    {
        $transaction = Transaction::with(['salesDetails', 'services'])->findOrFail($transactionId);

        $totalPart = $transaction->salesDetails->sum('sub_total');
        $totalJasa = $transaction->services ? $transaction->services->biaya_jasa : 0;

        $transaction->update([
            'total_sparepart' => $totalPart,
            'total_jasa'      => $totalJasa,
            'total_akhir'     => ($totalPart + $totalJasa) - ($transaction->diskon ?? 0),
        ]);
    }

    public function addPart(int $transactionId, int $sparepartId, int $qty): void
    {
        DB::transaction(function () use ($transactionId, $sparepartId, $qty) {
            $sparepart = Sparepart::findOrFail($sparepartId);

            if ($sparepart->stock_quantity < $qty) {
                throw new \RuntimeException('Stok kurang! Sisa: ' . $sparepart->stock_quantity);
            }

            $sparepart->decrement('stock_quantity', $qty);

            SalesDetails::create([
                'transaction_id'       => $transactionId,
                'sparepart_id'         => $sparepart->sparepart_id,
                'jumlah'               => $qty,
                'harga_satuan'         => $sparepart->selling_price,
                'current_buying_price' => $sparepart->buying_price,
                'sub_total'            => $sparepart->selling_price * $qty,
            ]);

            $this->updateTransactionTotal($transactionId);
        });
    }

    public function removePart(int $id): void
    {
        DB::transaction(function () use ($id) {
            $detail = SalesDetails::findOrFail($id);
            $transactionId = $detail->transaction_id;

            Sparepart::findOrFail($detail->sparepart_id)
                ->increment('stock_quantity', $detail->jumlah);

            $detail->delete();
            $this->updateTransactionTotal($transactionId);
        });
    }

    public function updateQty(int $id, int $newQty): void
    {
        DB::transaction(function () use ($id, $newQty) {
            $detail = SalesDetails::findOrFail($id);
            $transactionId = $detail->transaction_id;
            $sparepart = Sparepart::findOrFail($detail->sparepart_id);

            $oldQty = $detail->jumlah;
            $diff = $newQty - $oldQty;

            if ($diff > 0) {
                if ($sparepart->stock_quantity < $diff) {
                    throw new \RuntimeException('Stok tidak cukup untuk penambahan!');
                }
                $sparepart->decrement('stock_quantity', $diff);
            } elseif ($diff < 0) {
                $sparepart->increment('stock_quantity', abs($diff));
            }

            $detail->update([
                'jumlah'   => $newQty,
                'sub_total' => $sparepart->selling_price * $newQty,
            ]);

            $this->updateTransactionTotal($transactionId);
        });
    }
}
