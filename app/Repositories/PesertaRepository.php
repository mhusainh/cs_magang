<?php

namespace App\Repositories;

use App\Models\PesertaPpdb as Peserta;

class PesertaRepository
{
    public function __construct(private Peserta $model) {}
    public function create(array $data): Peserta
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Peserta
    {
        return $this->model->where('id', $id)->first();
    }

    public function findByUserId(int $userId): ?Peserta
    {
        return $this->model->where('user_id', $userId)->first();
    }

    public function update(Peserta $peserta, array $data): bool
    {
        return $peserta->update($data);
    }

    public function delete(Peserta $peserta): bool
    {
        return $peserta->delete();
    }

    public function getAll(): array
    {
        return Peserta::all()->toArray();
    }
} 
