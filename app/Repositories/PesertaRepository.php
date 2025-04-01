<?php

namespace App\Repositories;

use App\Models\PesertaPpdb;

class PesertaRepository
{
    public function create(array $data): PesertaPpdb
    {
        return PesertaPpdb::create($data);
    }

    public function findById(int $id): ?PesertaPpdb
    {
        return PesertaPpdb::where('id', $id)->first();
    }

    public function findByUserId(int $userId): ?PesertaPpdb
    {
        return PesertaPpdb::where('user_id', $userId)->first();
    }

    public function update(PesertaPpdb $peserta, array $data): bool
    {
        return $peserta->update($data);
    }

    public function delete(PesertaPpdb $peserta): bool
    {
        return $peserta->delete();
    }

    public function getAll(): array
    {
        return PesertaPpdb::all()->toArray();
    }
} 