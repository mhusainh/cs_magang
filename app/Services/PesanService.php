<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Repositories\PesanRepository;
use App\Http\Resources\Pesan\GetResource;

class PesanService
{
    public function __construct(
        private PesanRepository $pesanRepository
    ) {}

    public function getAll(): array
    {
        $result = $this->pesanRepository->getAll();
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
        $result = $this->pesanRepository->findById($id);
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

    public function getByUserId(int $userId): array
    {
        $result = $this->pesanRepository->getByUserId($userId);
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
        $result = $this->pesanRepository->create($data);
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
        $result = $this->pesanRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->pesanRepository->update($result, $data);
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
        $result = $this->pesanRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->pesanRepository->delete($result);
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
