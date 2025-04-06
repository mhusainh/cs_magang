<?php

namespace App\Repositories;

use App\Models\PekerjaanOrtu;
use Illuminate\Database\Eloquent\Collection;

class PekerjaanOrtuRepository
{
    public function __construct(private PekerjaanOrtu $model) {}

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?PekerjaanOrtu
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): PekerjaanOrtu
    {
        return $this->model->create($data);
    }

    public function update(PekerjaanOrtu $pekerjaanOrtu, array $data): bool
    {
        return $pekerjaanOrtu->update($data);
    }


    public function delete(PekerjaanOrtu $pekerjaanOrtu): bool
    {
        return $pekerjaanOrtu->delete();
    }
} 