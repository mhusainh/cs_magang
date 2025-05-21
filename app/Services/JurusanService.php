<?php

namespace App\Services;

use App\Http\Resources\Jurusan\GetDetailResource;
use App\Repositories\JurusanRepository;

class JurusanService
{
    public function __construct(
        private JurusanRepository $jurusanRepository
    ) {}

    public function getAll(array $filters = []): array
    {
        try {
            $jurusan = $this->jurusanRepository->getAll($filters);
            if(!$jurusan){
                return [
                   'success' => true,
                    'data' => null,
                   'message' => 'Tidak ada jurusan ditemukan'
                ];
            }

            
            $totalItems = $jurusan->total();
            $totalPages = $jurusan->lastPage();
            $currentPage = $jurusan->currentPage();
            $perPage = $jurusan->perPage();

            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'jenjang_sekolah' => $filters['jenjang'] ?? '',
                'sort_by' => $filters['sort_by'] ?? '',
                'sort_direction' => $filters['sort_direction'] ?? ''
            ];

            // Set pagination data
            $pagination = [
                'page' => $currentPage,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalPages
            ];

            return [
                'success' => true,
                'data' => GetDetailResource::collection($jurusan),
                'current_filters' => $currentFilters,
                'message' => 'Daftar jurusan berhasil diambil',
                'pagination' => $pagination
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $jurusan = $this->jurusanRepository->findById($id);

            if (!$jurusan) {
                return [
                    'success' => false,
                    'message' => 'Jurusan tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => new GetDetailResource($jurusan),
                'message' => 'Detail jurusan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $validation = $this->jurusanRepository->validation($data);
            if ($validation->isNotEmpty()) {
                return [
                   'success' => false,
                   'message' => 'Jurusan sudah ada'
                ];
            }
            $jurusan = $this->jurusanRepository->create($data);
            return [
                'success' => true,
                'data' => $jurusan,
                'message' => 'Jurusan berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $jurusan = $this->jurusanRepository->findById($data['id']);

            if (!$jurusan) {
                return [
                    'success' => false,
                    'message' => 'Jurusan tidak ditemukan'
                ];
            }

            $updated = $this->jurusanRepository->update($jurusan, $data);

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Jurusan berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $jurusan = $this->jurusanRepository->findById($id);

            if (!$jurusan) {
                return [
                    'success' => false,
                    'message' => 'Jurusan tidak ditemukan'
                ];
            }

            $this->jurusanRepository->delete($jurusan);

            return [
                'success' => true,
                'message' => 'Jurusan berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function getJurusanByJenjang($jenjang): array
    {
        try {
            $jurusan = $this->jurusanRepository->findbyJenjangSekolah($jenjang);

            if ($jurusan->isEmpty()) {
                return [
                    'success' => true,
                    'data' => [],
                    'message' => 'Tidak ada jurusan ditemukan untuk jenjang ini'
                ];
            }

            return [
                'success' => true,
                'data' => $jurusan->map(function ($item) {
                    return new GetDetailResource($item);
                }),
                'message' => 'Daftar jurusan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function getDeleted(): array
    {
        try {
            $jurusan = $this->jurusanRepository->getTrash();
            return [
                'success' => true,
                'data' => $jurusan->map(function ($item) {
                    return new GetDetailResource($item);
                }),
                'message' => 'Daftar jurusan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar jurusan: ' . $e->getMessage()
            ];
        }
    }

    public function restore(int $id): array
    {
        $jurusan = $this->jurusanRepository->findById($id);

        if (!$jurusan) {
            return [
                'success' => false,
                'message' => 'Jurusan tidak ditemukan'
            ];
        }
        $result = $this->jurusanRepository->restore($jurusan);

        if ($result) {
            return [
                'success' => true,
                'message' => 'Jurusan berhasil dipulihkan'
            ];
        }
        return [
            'success' => false,
            'message' => 'Gagal memulihkan jurusan',
        ];
    }
}
