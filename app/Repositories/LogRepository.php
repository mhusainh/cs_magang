<?php

namespace App\Repositories;

use App\Models\Log;
use Illuminate\Database\Eloquent\Collection;

class LogRepository
{
    public function __construct(private Log $model) {}

    public function findById(int $id): ?Log
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): Log
    {
        return $this->model->create($data);
    }

    public function update(Log $log, array $data): bool
    {
        return $log->update($data);
    }

    public function delete(Log $log): bool
    {
        return $log->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

} 