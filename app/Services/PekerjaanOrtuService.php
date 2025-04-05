<?php

namespace App\Services;

use App\Repositories\PekerjaanOrtuRepository;

class PekerjaanOrtuService
{
    public function __construct(
        private PekerjaanOrtuRepository $pekerjaanOrtuRepository
    ) {}

    public function getAll(): array
    {
        try {
            $data = $this->pekerjaanOrtuRepository->getAll();
            return [
                'success' => true,
                'data' => $data,
                'message' => 'Daftar pekerjaan ortu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $data = $this->pekerjaanOrtuRepository->findById($id);
            
            if (!$data) {
                return [
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Detail pekerjaan ortu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil data: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $pekerjaanOrtu = $this->pekerjaanOrtuRepository->create($data);
            return [
                'success' => true,
                'data' => $pekerjaanOrtu,
                'message' => 'Pekerjaan ortu berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat data: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $pekerjaanOrtu = $this->pekerjaanOrtuRepository->findById($data['id']);
            
            if (!$pekerjaanOrtu) {
                return [
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            $updated = $this->pekerjaanOrtuRepository->update($pekerjaanOrtu, $data);
            
            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Data berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $pekerjaanOrtu = $this->pekerjaanOrtuRepository->findById($id);
            
            if (!$pekerjaanOrtu) {
                return [
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ];
            }

            $this->pekerjaanOrtuRepository->delete($pekerjaanOrtu);
            
            return [
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ];
        }
    }
} 