<?php

namespace App\Services;

use App\Repositories\SparepartRepository;

class SparepartService
{
    public function __construct(
        private SparepartRepository $repository
    ) {}

    public function addStock(int $id, int $qtyMasuk, float $hargaBeliBaru): array
    {
        $sparepart = $this->repository->findById($id);

        $stokLama = $sparepart->stock_quantity;
        $hppLama  = $sparepart->buying_price;

        if ($stokLama <= 0) {
            $hppBaru = $hargaBeliBaru;
        } else {
            $totalNilai = ($stokLama * $hppLama) + ($qtyMasuk * $hargaBeliBaru);
            $totalQty   = $stokLama + $qtyMasuk;
            $hppBaru    = $totalNilai / $totalQty;
        }

        $sparepart->update([
            'stock_quantity' => $stokLama + $qtyMasuk,
            'buying_price'   => $hppBaru,
            'entry_date'     => now(),
        ]);

        $formatted = 'Rp ' . number_format($hppBaru, 0, ',', '.');

        return [
            'success' => true,
            'message' => "Stok bertambah! Harga modal disesuaikan menjadi {$formatted}",
            'hpp_baru' => $hppBaru,
        ];
    }
}
