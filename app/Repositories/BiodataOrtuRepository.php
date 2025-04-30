<?php

namespace App\Repositories;

use App\Models\BiodataOrtu;
use Illuminate\Database\Eloquent\Collection;

class BiodataOrtuRepository
{
    public function __construct(private BiodataOrtu $model) {}

    public function findById(int $id): ?BiodataOrtu
    {
        return $this->model->withTrashed()->where('id', $id)->first();
    }

    public function create(array $data): BiodataOrtu
    {
        return $this->model->create($data);
    }

    public function update(BiodataOrtu $biodataOrtu, array $data): bool
    {
        return $biodataOrtu->update($data);
    }

    public function delete(BiodataOrtu $biodataOrtu): bool
    {
        return $biodataOrtu->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getTrash(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore(BiodataOrtu $biodataOrtu): bool
    {
        return $biodataOrtu->restore();
    }
} 