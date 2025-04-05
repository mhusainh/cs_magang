<?php

namespace App\Services;

use App\Repositories\TagihanRepository;
use Illuminate\Support\Facades\Auth;

class TagihanService
{
    public function __construct(
        private TagihanRepository $tagihanRepository
    ) {
    }

    public function getById(int $id, int $userId): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($id, $userId);
            
            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $tagihan,
                'message' => 'Detail tagihan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            // Validasi VA number unik
            if ($this->tagihanRepository->vaNumberExists($data['va_number'])) {
                return [
                    'success' => false,
                    'message' => 'VA Number sudah digunakan'
                ];
            }

            $tagihan = $this->tagihanRepository->create($data);
            return [
                'success' => true,
                'data' => $tagihan,
                'message' => 'Tagihan berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($data['id']);
            
            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            // Validasi VA number unik kecuali untuk record yang sama
            if ($this->tagihanRepository->vaNumberExists($data['va_number'], $data['id'])) {
                return [
                    'success' => false,
                    'message' => 'VA Number sudah digunakan'
                ];
            }

            $updated = $this->tagihanRepository->update($tagihan, $data);
            
            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Tagihan berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($id);
            
            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            $this->tagihanRepository->delete($tagihan);
            
            return [
                'success' => true,
                'message' => 'Tagihan berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function getAll(int $userId): array
    {
        try {
            $tagihans = $this->tagihanRepository->getAll($userId);
            return [
                'success' => true,
                'data' => $tagihans,
                'message' => 'Tagihan user berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil tagihan: ' . $e->getMessage()
            ];
        }
    }
} 