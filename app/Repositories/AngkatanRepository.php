<?php

namespace App\Repositories;

use App\Models\Angkatan;
use Illuminate\Database\Eloquent\Collection;

class AngkatanRepository
{
    public function __construct(private Angkatan $model) {}

    public function create(array $data): Angkatan
    {
        return $this->model->create($data);
    }

    public function update(Angkatan $angkatan, array $data): bool
    {
        return $angkatan->update($data);
    }

    public function delete(Angkatan $angkatan): bool
    {
        return $angkatan->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function findById(int $id): ?Angkatan
    {
        return $this->model->find($id)->first();
    }

    public function angkatan():?Angkatan
    {
        return $this->model->where('angkatan', '!=', null)->first();
    }
} 
