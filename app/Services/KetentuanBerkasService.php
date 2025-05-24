<?php

namespace App\Services;

use App\Http\Resources\KetentuanBerkas\GetAllKetentuanBerkasResource;
use App\Models\Berkas;
use App\Models\KetentuanBerkas;
use App\Repositories\KetentuanBerkasRepository;
use Illuminate\Support\Facades\DB;

class KetentuanBerkasService
{
    public function __construct(private KetentuanBerkasRepository $ketentuanBerkasRepository) {}

    /**
     * Membuat ketentuan berkas baru
     */
    public function createKetentuanBerkas(array $data)
    {
        try {
            $ketentuanBerkas = $this->ketentuanBerkasRepository->createKetentuanBerkas($data);

            return [
                'success' => true,
                'message' => 'Berhasil membuat ketentuan berkas',
                'data' => $ketentuanBerkas
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Mendapatkan semua ketentuan berkas dengan fitur pencarian dan filter
     */
    public function getAllKetentuanBerkas(array $filters = [])
    {
        try {
            $result = $this->ketentuanBerkasRepository->getAllKetentuanBerkas($filters);
            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            $totalItems = $result->total();
            $totalPages = $result->lastPage();
            $currentPage = $result->currentPage();
            $perPage = $result->perPage();

            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'jenjang_sekolah' => $filters['jenjang'] ?? '',
                'is_required' => $filters['is_required'] ?? '',
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
                'message' => 'Berhasil mendapatkan semua ketentuan berkas',
                'data' => GetAllKetentuanBerkasResource::collection($result),
                'current_filters' => $currentFilters,
                'pagination' => $pagination
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Menghapus ketentuan berkas
     */
    public function deleteKetentuanBerkas($id)
    {
        try {
            $ketentuanBerkas = $this->ketentuanBerkasRepository->getKetentuanBerkasById($id);
            if (!$ketentuanBerkas) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            $this->ketentuanBerkasRepository->deleteKetentuanBerkas($id);

            return [
                'success' => true,
                'message' => 'Berhasil menghapus ketentuan berkas',
                'data' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan jenjang sekolah
     */
    public function getKetentuanBerkasByJenjang($jenjangSekolah)
    {
        try {

            $ketentuanBerkas = $this->ketentuanBerkasRepository->getKetentuanBerkasByJenjang($jenjangSekolah);
            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan ketentuan berkas',
                'data' => $ketentuanBerkas
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getKetentuanBerkasById($id)
    {
        try {
            $ketentuanBerkas = $this->ketentuanBerkasRepository->getKetentuanBerkasById($id);
            if (!$ketentuanBerkas) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan ketentuan berkas',
                'data' => $ketentuanBerkas
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Mengupdate ketentuan berkas
     */
    public function updateKetentuanBerkas($id, array $data)
    {
        try {
            $ketentuanBerkas = $this->ketentuanBerkasRepository->updateKetentuanBerkas($data);
            if (!$ketentuanBerkas) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            return [
                'success' => true,
                'message' => 'Berhasil mengupdate ketentuan berkas',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getDeleted(array $filters = [])
    {
        try {
            $result = $this->ketentuanBerkasRepository->getTrash($filters);
            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            $totalItems = $result->total();
            $totalPages = $result->lastPage();
            $currentPage = $result->currentPage();
            $perPage = $result->perPage();

            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'jenjang_sekolah' => $filters['jenjang'] ?? '',
                'is_required' => $filters['is_required'] ?? '',
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
                'message' => 'Berhasil mendapatkan semua ketentuan berkas',
                'data' => [
                    'ketentuan_berkas' => GetAllKetentuanBerkasResource::collection($result),
                    'current_filters' => $currentFilters
                ],
                'pagination' => $pagination
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan ketentuan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function restore($id)
    {
        $ketentuanBerkas = $this->ketentuanBerkasRepository->getKetentuanBerkasById($id);
        if (!$ketentuanBerkas) {
            return [
                'success' => false,
                'message' => 'Ketentuan berkas tidak ditemukan',
            ];
        }
        $result = $this->ketentuanBerkasRepository->restore($ketentuanBerkas);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Gagal mengembalikan ketentuan berkas',
            ];
        }
        return [
            'success' => true,
            'message' => 'Berhasil mengembalikan ketentuan berkas',
            'data' => $ketentuanBerkas
        ];
    }
}
