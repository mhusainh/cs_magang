<?php

namespace App\Services;

use App\Http\Resources\Jurusan\GetDetailResource;
use App\Repositories\JurusanRepository;

class JurusanService
{
    public function __construct(
        private JurusanRepository $jurusanRepository
    ) {
    }

    public function getAll(): array
    {
        try {
            $jurusan = $this->jurusanRepository->getAll();
            return [
                'success' => true,
                'data' => GetDetailResource::collection($jurusan),
                'message' => 'Daftar jurusan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $jurusan = $this->jurusanRepository->findById($id);
            
            if (!$jurusan) {
                return [
                    'success' => false,
                    'message' => 'Jurusan tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => new GetDetailResource($jurusan),
                'message' => 'Detail jurusan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $jurusan = $this->jurusanRepository->create($data);
            return [
                'success' => true,
                'data' => $jurusan,
                'message' => 'Jurusan berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $jurusan = $this->jurusanRepository->findById($data['id']);
            
            if (!$jurusan) {
                return [
                    'success' => false,
                    'message' => 'Jurusan tidak ditemukan'
                ];
            }

            $updated = $this->jurusanRepository->update($jurusan, $data);
            
            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Jurusan berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $jurusan = $this->jurusanRepository->findById($id);
            
            if (!$jurusan) {
                return [
                    'success' => false,
                    'message' => 'Jurusan tidak ditemukan'
                ];
            }

            $this->jurusanRepository->delete($jurusan);
            
            return [
                'success' => true,
                'message' => 'Jurusan berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function getJurusanByJenjang($jenjang): array
    {
        try {
            $jurusan = $this->jurusanRepository->findbyJenjangSekolah($jenjang);
            return [
               'success' => true,
                'data' => GetDetailResource::collection($jurusan),
               'message' => 'Daftar jurusan berhasil diambil'
            ]; 
        } catch (\Exception $e) {
            return [
              'success' => false,
              'message' => 'Gagal mengambil daftar jurusan: '. $e->getMessage()
            ];
        }
    }
} 