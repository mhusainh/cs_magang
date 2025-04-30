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

    public function getAll($filters = []): array
    {
        $result = $this->pesanRepository->getAll($filters);
        if ($result->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        // Set pagination data
        $pagination = [
            'page' => $result->currentPage(),
            'per_page' => $result->perPage(),
            'total_items' => $result->total(),
            'total_pages' => $result->lastPage()
        ];

        $currenFilters = [
            'search' => $filters['search'] ?? null,
            'start_date' => $filters['start_date'] ?? null,
            'end_date' => $filters['end_date'] ?? null,
            'is_read' => $filters['is_read'] ?? null,
            'sort_by' => $filters['sort_by'] ?? '',
            'sort_direction' => $filters['sort_direction'] ?? ''
        ];
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $result,
            'pagination' => $pagination,
            'current_filters' => $currenFilters,
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

    public function getDeleted($filters = []): array
    {
        $result = $this->pesanRepository->getTrash($filters);
        if ($result->isEmpty()) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        // Set pagination data
        $pagination = [
            'page' => $result->currentPage(),
            'per_page' => $result->perPage(),
            'total_items' => $result->total(),
            'total_pages' => $result->lastPage()
        ];

        $currenFilters = [
            'search' => $filters['search'] ?? null,
            'start_date' => $filters['start_date'] ?? null,
            'end_date' => $filters['end_date'] ?? null,
            'is_read' => $filters['is_read'] ?? null,
            'sort_by' => $filters['sort_by'] ?? '',
            'sort_direction' => $filters['sort_direction'] ?? ''
        ];
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => $result,
            'pagination' => $pagination,
            'current_filters' => $currenFilters,
        ];
    }

    public function restore($id): array
    {
        $pesan = $this->pesanRepository->findById($id);
        if (!$pesan) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->pesanRepository->restore($pesan);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal dipulihkan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil dipulihkan',
            'data' => $pesan,
        ];
    }
}
