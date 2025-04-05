<?php

namespace App\Repositories;

use App\Models\PesertaPpdb as Peserta;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class PesertaRepository
{
    public function __construct(private Peserta $model) {}

    public function create(array $data): Peserta
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Peserta
    {
        return $this->model->with([
            'jurusan1',
            'jurusan2',
            'biodataOrtu.pekerjaanAyah',
            'biodataOrtu.pekerjaanIbu',
            'biodataOrtu.penghasilanOrtu'
        ])->where('id', $id)->first();
    }

    public function findByUserId(int $userId): ?Peserta
    {
        return $this->model->with([
            'jurusan1',
            'jurusan2',
            'biodataOrtu.pekerjaanAyah',
            'biodataOrtu.pekerjaanIbu',
            'biodataOrtu.penghasilanOrtu'
        ])->where('user_id', $userId)->first();
    }

    public function update(Peserta $peserta, array $data): bool
    {
        return $peserta->update($data);
    }

    public function delete(Peserta $peserta): bool
    {
        return $peserta->delete();
    }

    // public function getAll(): Collection
    // {
    //     return $this->model->all();
    // }

    // public function getAllWithPagination(int $perPage = 10): LengthAwarePaginator
    // {
    //     return $this->model->paginate($perPage);
    // }

    // public function getAllWithRelations(): Collection
    // {
    //     return $this->model->with([
    //         'jurusan1',
    //         'jurusan2',
    //         'biodataOrtu.pekerjaanAyah',
    //         'biodataOrtu.pekerjaanIbu',
    //         'biodataOrtu.penghasilanOrtu'
    //     ])->get();
    // }

    public function getAllWithRelationsAndPagination(int $perPage = 10): LengthAwarePaginator
    {
        return $this->model->with([
            'jurusan1',
            'jurusan2',
            'biodataOrtu.pekerjaanAyah',
            'biodataOrtu.pekerjaanIbu',
            'biodataOrtu.penghasilanOrtu'
        ])->paginate($perPage);
    }
}
