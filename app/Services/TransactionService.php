<?php

namespace App\Services;

use App\Models\Transaction;
use App\Repositories\TransactionRepository;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function __construct(
        private TransactionRepository $repository
    ) {}

    public function processPayment(int $id, float $bayar, ?float $diskon = 0): Transaction
    {
        $transaction = $this->repository->findById($id);

        $totalPart  = $transaction->total_sparepart ?? 0;
        $totalJasa  = $transaction->total_jasa ?? 0;
        $diskon     = $diskon ?? 0;
        $totalAkhir = ($totalPart + $totalJasa) - $diskon;

        if ($bayar < $totalAkhir) {
            throw new \RuntimeException('Uang pembayaran kurang!');
        }

        return DB::transaction(function () use ($transaction, $diskon, $totalAkhir) {
            $transaction->update([
                'diskon'            => $diskon,
                'total_akhir'       => $totalAkhir,
                'status_pembayaran' => 'lunas',
            ]);

            if ($transaction->services) {
                $transaction->services->update([
                    'status'        => 'selesai',
                    'waktu_selesai' => now(),
                ]);
            }

            return $transaction->fresh();
        });
    }
}
