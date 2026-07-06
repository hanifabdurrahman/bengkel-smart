<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function __construct(Transaction $model)
    {
        parent::__construct($model);
    }

    public function findWithRelations($id)
    {
        return $this->newQuery()
            ->with(['customer', 'services', 'salesDetails.sparepart'])
            ->findOrFail($id);
    }

    public function getRevenueByPeriod($startDate, $endDate, ?int $year = null, ?int $month = null)
    {
        $query = $this->newQuery()
            ->where('status_pembayaran', 'lunas');

        if ($year && $month) {
            $query->whereYear('created_at', $year)
                ->whereMonth('created_at', $month);
        } elseif ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->sum('total_akhir');
    }

    public function getServiceTraffic($startDate, $endDate)
    {
        return $this->newQuery()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('status_pembayaran', 'lunas')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->pluck('count', 'date');
    }
}
