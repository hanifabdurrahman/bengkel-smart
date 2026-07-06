<?php

namespace App\Repositories;

use App\Models\Service;

class ServiceRepository extends BaseRepository
{
    public function __construct(Service $model)
    {
        parent::__construct($model);
    }

    public function getActiveServices(?string $search = null)
    {
        $query = $this->newQuery()
            ->with('customer')
            ->whereIn('status', ['antri', 'proses']);

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('license_plate', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('created_at', 'asc')->paginate(10);
    }

    public function findWithRelations($id)
    {
        return $this->newQuery()
            ->with(['customer', 'transaction.salesDetails.sparepart'])
            ->findOrFail($id);
    }

    public function getPendingPaymentServices(?string $search = null)
    {
        $query = $this->newQuery()
            ->with(['customer', 'transaction'])
            ->where('status', 'selesai')
            ->whereHas('transaction', fn($q) => $q->where('status_pembayaran', 'pending'));

        if ($search) {
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('license_plate', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('waktu_selesai', 'desc')->paginate(10);
    }
}
