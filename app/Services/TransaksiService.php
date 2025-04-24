<?php

namespace App\Services;

use Exception;
use App\Helpers\JWT;
use App\Http\Resources\Transaksi\PeringkatResource;
use App\Http\Resources\Transaksi\RiwayatResource;
use GuzzleHttp\Client;
use App\Repositories\TransaksiRepository;

class TransaksiService
{
    public function __construct(
        private TransaksiRepository $transaksiRepository
    ) {}

    public function getAll(int $userId): array
    {
        try {
            $transaksi = $this->transaksiRepository->getAll($userId);
            return [
                'success' => true,
                'data' => $transaksi,
                'message' => 'Daftar transaksi berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar transaksi: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $transaksi = $this->transaksiRepository->findById($id);

            if (!$transaksi) {
                return [
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $transaksi,
                'message' => 'Detail transaksi berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail transaksi: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $transaksi = $this->transaksiRepository->create($data);
            return [
                'success' => true,
                'data' => $transaksi,
                'message' => 'Transaksi berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $transaksi = $this->transaksiRepository->findById($data['id']);

            if (!$transaksi) {
                return [
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ];
            }

            $updated = $this->transaksiRepository->update($transaksi, $data);

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Transaksi berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui transaksi: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $this->transaksiRepository->delete($id);

            return [
                'success' => true,
                'message' => 'Transaksi berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ];
        }
    }

    public function getByUserId(int $userId, $filters = []): array
    {
        try {
            $transaksi = $this->transaksiRepository->findUserId($userId, $filters);
            if ($transaksi->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => RiwayatResource::collection($transaksi),
                'message' => 'Detail transaksi berhasil diambil',
                'pagination' => [
                    'page' => $transaksi->currentPage(),
                    'per_page' => $transaksi->perPage(),
                    'total_items' => $transaksi->total(),
                    'total_pages' => $transaksi->lastPage()
                ],
                'current_filters' => [
                    'start_date' => $filters['start_date'] ?? '',
                    'end_date' => $filters['end_date'] ?? '',
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail transaksi: ' . $e->getMessage()
            ];
        }
    }

    public function getAllBookVee(): array
    {
        try {
            $transaksi = $this->transaksiRepository->findBookVeeWithPeserta();
            if ($transaksi->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => PeringkatResource::collection($transaksi->items()),
                'message' => 'Detail transaksi berhasil diambil',
                'pagination' => [
                    'page' => $transaksi->currentPage(),
                    'per_page' => $transaksi->perPage(),
                    'total_items' => $transaksi->total(),
                    'total_pages' => $transaksi->lastPage()
                ]
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail transaksi: ' . $e->getMessage()
            ];
        }
    }
}
