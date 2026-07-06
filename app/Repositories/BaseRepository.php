<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById($id): Model
    {
        return $this->model->where('workshop_id', Auth::user()->workshop_id)->findOrFail($id);
    }

    public function findByIdWithoutScope($id): Model
    {
        return $this->model->findOrFail($id);
    }

    public function newQuery()
    {
        return $this->model->where('workshop_id', Auth::user()->workshop_id);
    }

    public function create(array $data): Model
    {
        $data['workshop_id'] = Auth::user()->workshop_id;
        return $this->model->create($data);
    }

    public function update($id, array $data): Model
    {
        $record = $this->findById($id);
        $record->update($data);
        return $record;
    }

    public function delete($id): bool
    {
        $record = $this->findById($id);
        return $record->delete();
    }

    public function paginate($perPage = 10)
    {
        return $this->newQuery()->paginate($perPage);
    }
}
