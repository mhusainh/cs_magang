<?php

namespace App\Services;

use App\Http\Resources\BiodataOrtu\GetDetailResource;
use App\Repositories\BiodataOrtuRepository;

class BiodataOrtuService
{
    public function __construct(
        private BiodataOrtuRepository $biodataOrtuRepository
    ) {}

    public function getAll(): array
    {
        try {
            $biodataOrtu = $this->biodataOrtuRepository->getAll();
            return [
                'success' => true,
                'data' => GetDetailResource::collection($biodataOrtu),
                'message' => 'Daftar biodataOrtu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar biodataOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $biodataOrtu = $this->biodataOrtuRepository->findById($id);

            if (!$biodataOrtu) {
                return [
                    'success' => false,
                    'message' => 'biodataOrtu tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => new GetDetailResource($biodataOrtu),
                'message' => 'Detail biodataOrtu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail biodataOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $biodataOrtu = $this->biodataOrtuRepository->create($data);
            return [
                'success' => true,
                'data' => $biodataOrtu,
                'message' => 'biodataOrtu berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat biodataOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $biodataOrtu = $this->biodataOrtuRepository->findById($data['id']);

            if (!$biodataOrtu) {
                return [
                    'success' => false,
                    'message' => 'biodataOrtu tidak ditemukan'
                ];
            }

            $updated = $this->biodataOrtuRepository->update($biodataOrtu, $data);
            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui biodataOrtu'
                ];
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'biodataOrtu berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui biodataOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $biodataOrtu = $this->biodataOrtuRepository->findById($id);

            if (!$biodataOrtu) {
                return [
                    'success' => false,
                    'message' => 'biodataOrtu tidak ditemukan'
                ];
            }

            $this->biodataOrtuRepository->delete($biodataOrtu);

            return [
                'success' => true,
                'message' => 'biodataOrtu berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus biodataOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function getDeleted(): array
    {
        try {
            $biodataOrtu = $this->biodataOrtuRepository->getTrash();
            return [
                'success' => true,
                'data' => GetDetailResource::collection($biodataOrtu),
                'message' => 'Daftar biodataOrtu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar biodataOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function restore(int $id): array
    {
        $biodataOrtu = $this->biodataOrtuRepository->findById($id);

        if (!$biodataOrtu) {
            return [
                'success' => false,
                'message' => 'biodataOrtu tidak ditemukan'
            ];
        }
        $result = $this->biodataOrtuRepository->restore($biodataOrtu);

        if (!$result) {
            return [
                'success' => false,
                'message' => 'Gagal mengembalikan biodataOrtu'
            ];
        }
        return [
            'success' => true,
            'data' => new GetDetailResource($biodataOrtu),
            'message' => 'biodataOrtu berhasil dikembalikan'
        ];
    }
}
