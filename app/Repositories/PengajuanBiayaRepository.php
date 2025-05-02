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

    public function getByUser(string $jenjang_sekolah = null, string $jurusan): ?PengajuanBiaya
    {
        return $this->model
            ->where('jenjang_sekolah', $jenjang_sekolah)
            ->where('jurusan', $jurusan)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getBookVee():?PengajuanBiaya
    {
        return $this->model
            ->where('jurusan', 'unggulan')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public function getReguler($data):?PengajuanBiaya
    {
        return $this->model
            ->where('jurusan', 'reguler')
            ->where('jenjang_sekolah', $data?? null)
            ->first();
    }
}
