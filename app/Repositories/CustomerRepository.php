<?php

namespace App\Repositories;

use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class CustomerRepository extends BaseRepository
{
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function search(?string $search = null)
    {
        $query = $this->newQuery();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'LIKE', "%{$search}%")
                    ->orWhere('license_plate', 'LIKE', "%{$search}%");
            });
        }

        return $query->orderBy('customer_id', 'DESC')->paginate(10);
    }

    public function findByPlate(string $licensePlate)
    {
        return $this->newQuery()
            ->where('license_plate', 'LIKE', "%{$licensePlate}%")
            ->orWhereRaw("REPLACE(license_plate, ' ', '') = ?", [strtoupper(str_replace(' ', '', $licensePlate))])
            ->first();
    }

    public function searchAjax(string $query)
    {
        $cleanQuery = strtoupper(str_replace(' ', '', $query));

        return $this->newQuery()
            ->where(function ($q) use ($query, $cleanQuery) {
                $q->where('license_plate', 'LIKE', "%{$query}%")
                    ->orWhereRaw("REPLACE(license_plate, ' ', '') = ?", [$cleanQuery]);
            })
            ->first();
    }
}
