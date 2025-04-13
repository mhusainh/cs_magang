<?php

namespace App\Repositories;

use App\Models\KetentuanBerkas;
use Illuminate\Support\Collection; 

class KetentuanBerkasRepository
{  
    public function __construct(private KetentuanBerkas $model){}
    /**
     * Mendapatkan semua ketentuan berkas
     */
    public function getAllKetentuanBerkas(): Collection
    {
        return $this->model->all();
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan jenjang sekolah
     */
    public function getKetentuanBerkasByJenjang($jenjangSekolah): Collection
    {
        return $this->model->where('jenjang_sekolah', $jenjangSekolah)->get();
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getKetentuanBerkasById($id): ?KetentuanBerkas
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Membuat ketentuan berkas baru
     */
    public function createKetentuanBerkas(array $data): KetentuanBerkas
    {
        return $this->model->create($data);
    }

    /**
     * Mengupdate ketentuan berkas
     */
    public function updateKetentuanBerkas(array $data): bool
    {
        return$this->model->update($data);      
    }

    public function deleteKetentuanBerkas($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }
}