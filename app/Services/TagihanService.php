<?php

namespace App\Services;

use App\Repositories\TagihanRepository;

class TagihanService
{
    public function __construct(
        private TagihanRepository $tagihanRepository,
        private QrisService $qrisService
    ) {}

    public function getById(int $id, int $userId): array
    {
        try {
            $tagihan = $this->tagihanRepository->findUserById($id, $userId);

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
            $data = [
                'user_id' => $data['user_id'],
                'nama_tagihan' => $data['nama_tagihan'],
                'total' => $data['total'],
                'created_time' => now()->format('s') . substr(now()->format('u'), 0, 6),
                'va_number' => $_ENV['QRIS_VA_NUMBER'] . str_pad(random_int(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
            ];

            $tagihan = $this->tagihanRepository->create($data);
            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Gagal membuat tagihan'
                ];
            }

            // Generate QRIS terlebih dahulu
            $qrisResult = $this->qrisService->generateQris($data);
            if (!$qrisResult['success']) {
                return $qrisResult;
            }

            // Tambahkan data QRIS ke data transaksi
            $data['transaction_qr_id'] = $qrisResult['data']['transactionQrId'];
            $data['qr_data'] = $qrisResult['data']['rawQrData'];

            $tagihan = $this->tagihanRepository->update($tagihan, $data);
            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui tagihan'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'va_number' => $data['va_number'],
                    'qr_data' => $qrisResult['data']['rawQrData'],
                ],
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
            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui tagihan'
                ];
            }

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
