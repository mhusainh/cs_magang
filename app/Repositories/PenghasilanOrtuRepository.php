<?php

namespace App\Repositories;

use App\Models\PenghasilanOrtu;
use Illuminate\Database\Eloquent\Collection;

class PenghasilanOrtuRepository
{
    public function __construct(private PenghasilanOrtu $model) {}

    public function findById(int $id): ?PenghasilanOrtu
    {
        return $this->model->withTrashed()->where('id', $id)->first();
    }

    public function create(array $data): PenghasilanOrtu
    {
        return $this->model->create($data);
    }

    public function update(PenghasilanOrtu $penghasilanOrtu, array $data): bool
    {
        return $penghasilanOrtu->update($data);
    }

    public function delete(PenghasilanOrtu $penghasilanOrtu): bool
    {
        return $penghasilanOrtu->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getTrash(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore(PenghasilanOrtu $penghasilanOrtu): bool
    {
        return $penghasilanOrtu->restore();
    }
} 
