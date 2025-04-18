<?php

namespace App\Repositories;

use App\Models\BiayaPendaftaran;
use Illuminate\Database\Eloquent\Collection;

class BiayaPendaftaranRepository
{
    public function __construct(private BiayaPendaftaran $model) {}

    public function findById(int $id): ?BiayaPendaftaran
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): BiayaPendaftaran
    {
        return $this->model->create($data);
    }

    public function update(BiayaPendaftaran $biayaPendaftaran, array $data): bool
    {
        return $biayaPendaftaran->update($data);
    }

    public function delete(BiayaPendaftaran $biayaPendaftaran): bool
    {
        return $biayaPendaftaran->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

} 