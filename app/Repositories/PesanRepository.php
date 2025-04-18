<?php

namespace App\Repositories;

use App\Models\Pesan;
use Illuminate\Database\Eloquent\Collection;

class PesanRepository
{
    public function __construct(private Pesan $model) {}

    public function findById(int $id): ?Pesan
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): Pesan
    {
        return $this->model->create($data);
    }

    public function update(Pesan $pesan, array $data): bool
    {
        return $pesan->update($data);
    }

    public function delete(Pesan $pesan): bool
    {
        return $pesan->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }
} 