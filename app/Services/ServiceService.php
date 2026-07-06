<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Transaction;
use App\Repositories\ServiceRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceService
{
    public function __construct(
        private ServiceRepository $repository
    ) {}

    public function createService(array $data): Service
    {
        return DB::transaction(function () use ($data) {
            $workshopId = Auth::user()->workshop_id;

            $customer = Customer::firstOrCreate(
                [
                    'license_plate' => strtoupper($data['license_plate']),
                    'workshop_id'   => $workshopId,
                ],
                [
                    'customer_name' => $data['customer_name'],
                    'phone_number'  => $data['phone_number'] ?? null,
                    'address'       => $data['address'] ?? null,
                    'vehicle'       => $data['vehicle'] ?? null,
                    'year'          => $data['year'] ?? null,
                ]
            );

            $transaction = Transaction::create([
                'workshop_id'       => $workshopId,
                'customer_id'       => $customer->customer_id,
                'tanggal'           => now(),
                'jenis_transaksi'   => 'servis',
                'status_pembayaran' => 'pending',
                'total_akhir'       => 0,
            ]);

            $service = Service::create([
                'workshop_id'    => $workshopId,
                'customer_id'    => $customer->customer_id,
                'transaction_id' => $transaction->transaction_id,
                'kode_servis'    => 'SRV-' . date('YmdHis') . '-' . rand(100, 999),
                'tanggal_masuk'  => now(),
                'keluhan'        => $data['keluhan'],
                'jenis_servis'   => $data['jenis_servis'] ?? null,
                'status'         => 'antri',
                'biaya_jasa'     => 0,
            ]);

            return $service;
        });
    }

    public function updateStatus(int $id, string $status): Service
    {
        $service = $this->repository->findById($id);
        $service->status = $status;

        if ($status === 'selesai') {
            $service->waktu_selesai = now();
        }

        $service->save();

        return $service;
    }

    public function updateJasa(int $id, float $biayaJasa): Service
    {
        return DB::transaction(function () use ($id, $biayaJasa) {
            $service = $this->repository->findById($id);
            $service->biaya_jasa = $biayaJasa;
            $service->save();

            $transaction = Transaction::find($service->transaction_id);
            if ($transaction) {
                $totalSparepart = $transaction->salesDetails()->sum('sub_total');
                $transaction->total_jasa = $biayaJasa;
                $transaction->total_akhir = ($totalSparepart + $biayaJasa) - ($transaction->diskon ?? 0);
                $transaction->save();
            }

            return $service;
        });
    }

    public function cancelService(int $id): void
    {
        DB::transaction(function () use ($id) {
            $service = $this->repository->findById($id);

            $transaction = Transaction::with('salesDetails')->find($service->transaction_id);

            if ($transaction) {
                foreach ($transaction->salesDetails as $detail) {
                    Sparepart::where('workshop_id', Auth::user()->workshop_id)
                        ->find($detail->sparepart_id)
                        ?->increment('stock', $detail->quantity);
                }

                $transaction->salesDetails()->delete();
                $transaction->delete();
            }

            $service->delete();
        });
    }
}
