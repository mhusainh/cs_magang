<?php

namespace App\Services;

use App\Repositories\AngkatanRepository;

class AngkatanService
{
    public function __construct(
        private AngkatanRepository $angkatanRepository
    ) {}

    public function getAll(): array
    {
        try {
            $angkatan = $this->angkatanRepository->getAll();
            return [
                'success' => true,
                'data' => $angkatan,
                
                'message' => 'Daftar angkatan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar angkatan: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $existingAngkatan = $this->angkatanRepository->getAll();
            if ($existingAngkatan->count() > 0) {
                return [
                   'success' => false,
                   'message' => 'angkatan sudah ada'
                ];
            }
            $angkatan = $this->angkatanRepository->create($data);
            return [
                'success' => true,
                'data' => $angkatan,
                'message' => 'angkatan berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat angkatan: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $angkatan = $this->angkatanRepository->findById($data['id']);

            if (!$angkatan) {
                return [
                    'success' => false,
                    'message' => 'angkatan tidak ditemukan'
                ];
            }

            $updated = $this->angkatanRepository->update($angkatan, $data);

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'angkatan berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui angkatan: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $angkatan = $this->angkatanRepository->findById($id);

            if (!$angkatan) {
                return [
                    'success' => false,
                    'message' => 'angkatan tidak ditemukan'
                ];
            }

            $this->angkatanRepository->delete($angkatan);

            return [
                'success' => true,
                'message' => 'angkatan berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus angkatan: ' . $e->getMessage()
            ];
        }
    }

    public function angkatanAktif()
    {
        $angkatan = $this->angkatanRepository->angkatan();
        if (!$angkatan) {
            return [
               'success' => false,
               'message' => 'PPDB belum dibuka'
            ];
        }

        return [
          'success' => true,
          'data' => $angkatan,
          'message' => 'PPDB sedang dibuka'
        ];
    }
}
