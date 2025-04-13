<?php

namespace App\Repositories;

use App\Models\Berkas;
use Illuminate\Support\Collection; 

class BerkasRepository
{
    public function __construct(private Berkas $model){}
    /**
     * Mendapatkan semua ketentuan berkas
     */
    public function getAllBerkas(): Collection
    {
        return Berkas::all();
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getBerkasById($id): ?Berkas
    {
        return $this->model->where('id', $id);
    }

    public function getBerkasByPesertaId($pesertaId): ?Berkas
    {
        return $this->model->where('peserta_id', $pesertaId);
    }

    /**
     * Mendapatkan berkas berdasarkan peserta ID dan ketentuan berkas ID
     */
    public function getBerkasByPesertaAndKetentuanId($pesertaId, $ketentuanBerkasId): Berkas
    {
        return $this->model->where('peserta_id', $pesertaId)
            ->where('kententuan_berkas_id', $ketentuanBerkasId)
            ->first();
    }

    /**
     * Membuat berkas baru
     */
    public function createBerkas(array $data): Berkas
    {
        return $this->model->create($data);
    }

    public function updateBerkas(array $data): bool
    {
        return $this->model->update($data);
    }
    /**
     * Menghapus berkas
     */
    public function deleteBerkas($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }
}