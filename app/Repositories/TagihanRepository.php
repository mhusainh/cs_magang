<?php

namespace App\Repositories;

use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Collection;

class TagihanRepository
{
    public function __construct(private Tagihan $model) {}

    public function findUserById(int $id, int $userId): ?Tagihan
    {
        return $this->model->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }
    public function findById(int $id): ?Tagihan
    {
        return $this->model->where('id', $id)->first();
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Tagihan $tagihan, array $data)
    {
        return $tagihan->update($data);
    }

    public function delete(Tagihan $tagihan)
    {
        return $tagihan->delete();
    }

    public function vaNumberExists(string $vaNumber, ?int $excludeId = null): bool
    {
        $query = $this->model->where('va_number', $vaNumber);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function getAll(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function getByQrData(string $qrData): ?Tagihan
    {
        return $this->model->where('qr_data', $qrData)->first();
    }
}
