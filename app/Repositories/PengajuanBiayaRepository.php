<?php

namespace App\Repositories;

use App\Models\PengajuanBiaya;
use Illuminate\Database\Eloquent\Collection;

class PengajuanBiayaRepository
{
    public function __construct(private PengajuanBiaya $model) {}

    public function findById(int $id): ?PengajuanBiaya
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): PengajuanBiaya
    {
        return $this->model->create($data);
    }

    public function update(PengajuanBiaya $pengajuanBiaya, array $data): bool
    {
        return $pengajuanBiaya->update($data);
    }

    public function delete(PengajuanBiaya $pengajuanBiaya): bool
    {
        return $pengajuanBiaya->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

} 