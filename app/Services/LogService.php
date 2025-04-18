<?php

namespace App\Services;

use App\Http\Resources\Log\GetResource;
use App\Repositories\LogRepository;

class LogService
{
    public function __construct(
        private LogRepository $logRepository
    ) {}

    public function getAll(): array
    {
        $result = $this->logRepository->getAll();
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $result,
        ];
    }

    public function getById($id): array
    {
        $result = $this->logRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $result,
        ];
    }

    public function create($data): array
    {
        $result = $this->logRepository->create($data);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal ditambahkan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => $result,
        ];
    }

    public function update($id, $data): array
    {
        $result = $this->logRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->logRepository->update($result, $data);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal diubah',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil diubah',
            'data' => $result,
        ];
    }

    public function delete($id): array
    {
        $result = $this->logRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->logRepository->delete($result);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal dihapus',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil dihapus',
            'data' => null,
        ];
    }
}
