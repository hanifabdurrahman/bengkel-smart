<?php

namespace App\Repositories;

use App\Models\Sparepart;

class SparepartRepository extends BaseRepository
{
    public function __construct(Sparepart $model)
    {
        parent::__construct($model);
    }

    public function search(?string $keyword = null, ?string $filter = null)
    {
        $query = $this->newQuery()->latest();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('sparepart_name', 'like', "%{$keyword}%")
                    ->orWhere('sparepart_code', 'like', "%{$keyword}%")
                    ->orWhere('rack_location', 'like', "%{$keyword}%");
            });
        }

        if ($filter === 'low_stock') {
            $query->where('stock_quantity', '<=', 5);
        }

        return $query->orderBy('sparepart_name', 'asc')->paginate(10);
    }

    public function lowStockExists(): bool
    {
        return $this->model->where('workshop_id', auth()->user()->workshop_id)
            ->where('stock_quantity', '<=', 5)
            ->exists();
    }
}
