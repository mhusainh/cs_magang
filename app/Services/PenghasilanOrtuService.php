<?php

namespace App\Services;

use App\Http\Resources\PenghasilanOrtu\GetDetailResource;
use App\Repositories\PenghasilanOrtuRepository;

class PenghasilanOrtuService
{
    public function __construct(
        private PenghasilanOrtuRepository $penghasilanOrtuRepository
    ) {}

    public function getAll(): array
    {
        try {
            $penghasilanOrtu = $this->penghasilanOrtuRepository->getAll();
            return [
                'success' => true,
                'data' => GetDetailResource::collection($penghasilanOrtu),
                'message' => 'Daftar penghasilanOrtu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar penghasilanOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $penghasilanOrtu = $this->penghasilanOrtuRepository->findById($id);

            if (!$penghasilanOrtu) {
                return [
                    'success' => false,
                    'message' => 'penghasilanOrtu tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => new GetDetailResource($penghasilanOrtu),
                'message' => 'Detail penghasilanOrtu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail penghasilanOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $penghasilanOrtu = $this->penghasilanOrtuRepository->create($data);
            if (!$penghasilanOrtu) {
                return [
                    'success' => false,
                    'message' => 'penghasilanOrtu gagal dibuat'
                ];
            }
            return [
                'success' => true,
                'data' => $penghasilanOrtu,
                'message' => 'penghasilanOrtu berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat penghasilanOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function update(int $id, array $data): array
    {
        try {
            $penghasilanOrtu = $this->penghasilanOrtuRepository->findById($id);

            if (!$penghasilanOrtu) {
                return [
                    'success' => false,
                    'message' => 'penghasilanOrtu tidak ditemukan'
                ];
            }

            $updated = $this->penghasilanOrtuRepository->update($penghasilanOrtu, $data);

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'penghasilanOrtu berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui penghasilanOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $penghasilanOrtu = $this->penghasilanOrtuRepository->findById($id);

            if (!$penghasilanOrtu) {
                return [
                    'success' => false,
                    'message' => 'penghasilanOrtu tidak ditemukan'
                ];
            }

            $this->penghasilanOrtuRepository->delete($penghasilanOrtu);

            return [
                'success' => true,
                'message' => 'penghasilanOrtu berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus penghasilanOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function getDeleted(): array
    {
        try {
            $penghasilanOrtu = $this->penghasilanOrtuRepository->getTrash();
            return [
                'success' => true,
                'data' => GetDetailResource::collection($penghasilanOrtu),
                'message' => 'Daftar penghasilanOrtu berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar penghasilanOrtu: ' . $e->getMessage()
            ];
        }
    }

    public function restore(int $id): array
    {
        $penghasilanOrtu = $this->penghasilanOrtuRepository->findById($id);

        if (!$penghasilanOrtu) {
            return [
                'success' => false,
                'message' => 'penghasilanOrtu tidak ditemukan'
            ];
        }

        $result = $this->penghasilanOrtuRepository->restore($penghasilanOrtu);
        if ($result) {
            return [
                'success' => true,
                'message' => 'penghasilanOrtu berhasil dikembalikan'
            ];
        }
        return [
            'success' => false,
            'message' => 'penghasilanOrtu gagal dikembalikan',
            'data' => new GetDetailResource($penghasilanOrtu)
        ];
    }
}
