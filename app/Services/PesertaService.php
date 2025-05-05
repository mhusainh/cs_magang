<?php

namespace App\Services;


use App\Repositories\PesertaRepository;
use App\Http\Resources\Peserta\GetDetailResource;
use App\Http\Resources\Peserta\GetResource;
use App\Repositories\JurusanRepository;
use App\Http\Resources\Transaksi\PeringkatResource;

class PesertaService
{
    public function __construct(
        private PesertaRepository $pesertaRepository,
        private JurusanRepository $jurusanRepository
    ) {}

    public function create(array $data): array
    {
        try {
            $this->pesertaRepository->create($data);

            return [
                'success' => true,
                'message' => 'Peserta berhasil ditambahkan'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan peserta'
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $peserta = $this->pesertaRepository->findById($id);
            if (!$peserta) {
                return [
                    'code' => 200,
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ];
            }
            
            return [
                'success' => true,
                'data' => new GetDetailResource($peserta),
                'message' => 'Peserta berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta'
            ];
        }
    }

    public function getByUserId(int $userId): array
    {
        $peserta = $this->pesertaRepository->findByUserId($userId);

        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => new GetDetailResource($peserta),
            'message' => 'Peserta berhasil diambil'
        ];
    }

    public function update(int $id, array $data): array
    {
        $peserta = $this->pesertaRepository->findById($id);

        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        try {
            $this->pesertaRepository->update($peserta, $data);

            return [
                'success' => true,
                'message' => 'Peserta berhasil diperbarui',
                'nama_peserta' => $peserta->nama,
                'jenjang_sekolah' => $peserta->jenjang_sekolah,
                'user_id' => $peserta->user_id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui peserta'
            ];
        }
    }

    public function delete(int $id): array
    {
        $peserta = $this->pesertaRepository->findById($id);

        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        try {
            $this->pesertaRepository->delete($peserta);

            return [
                'success' => true,
                'message' => 'Peserta berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus peserta'
            ];
        }
    }

    public function getAll($filters = []): array
    {
        try {
            $peserta = $this->pesertaRepository->getAll($filters);
            if ($peserta->isEmpty()) {
                return [
                    'code' => 200,
                    'success' => false,
                    'message' => 'Tidak ada data peserta'
                ];
            }

            // Set pagination data
            $pagination = [
                'page' => $peserta->currentPage(),
                'per_page' => $peserta->perPage(),
                'total_items' => $peserta->total(),
                'total_pages' => $peserta->lastPage()
            ];

            // Set current filters untuk response
            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'start_date' => $filters['start_date'] ?? '',
                'end_date' => $filters['end_date'] ?? '',
                'jenjang_sekolah' => $filters['jenjang_sekolah'] ?? '',
                'angkatan' => $filters['angkatan']?? '',
                'sort_by' => $filters['sort_by'] ?? '',
                'sort_direction' => $filters['sort_direction'] ?? ''
            ];

            return [
                'code' => 200,
                'success' => true,
                'data' => GetResource::collection($peserta),
                'message' => 'Data peserta berhasil diambil',
                'pagination' => $pagination,
                'current_filters' => $currentFilters
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta'
            ];
        }
    }

    public function getDeleted($filters = []): array
    {
        try {
            $peserta = $this->pesertaRepository->getTrash($filters);
            if ($peserta->isEmpty()) {
                return [
                    'code' => 200,
                    'success' => false,
                    'message' => 'Tidak ada data peserta'
                ];
            }

            // Set pagination data
            $pagination = [
                'page' => $peserta->currentPage(),
                'per_page' => $peserta->perPage(),
                'total_items' => $peserta->total(),
                'total_pages' => $peserta->lastPage()
            ];

            // Set current filters untuk response
            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'start_date' => $filters['start_date'] ?? '',
                'end_date' => $filters['end_date'] ?? '',
                'jenjang_sekolah' => $filters['jenjang_sekolah'] ?? '',
                'angkatan' => $filters['angkatan']?? '',
                'sort_by' => $filters['sort_by'] ?? '',
                'sort_direction' => $filters['sort_direction'] ?? ''
            ];

            return [
                'code' => 200,
                'success' => true,
                'data' => GetResource::collection($peserta),
                'message' => 'Data peserta berhasil diambil',
                'pagination' => $pagination,
                'current_filters' => $currentFilters
            ];
        } catch (\Exception $e) {
            return [
                'code' => 500,
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta'
            ];
        }
    }

    public function restore(int $id): array
    {
        $peserta = $this->pesertaRepository->findById($id);

        if (!$peserta) {
            return [
               'success' => false,
               'message' => 'Peserta tidak ditemukan'
            ];
        }

        $result = $this->pesertaRepository->restore($peserta);
        if (!$result) {
            return [
               'success' => false,
               'message' => 'Peserta gagal dikembalikan'
            ];
        }

        return [
            'success' => true,
            'data' => new GetDetailResource($peserta),
            'message' => 'Peserta berhasil dikembalikan'
        ];
    }

    public function getPeringkat(int $jurusan1_id, string $jenjang_sekolah): array
    {
        try {
            // Gunakan current_user_id untuk mendapatkan peringkat user saat ini
            $transaksi = $this->pesertaRepository->GetPeringkat($jurusan1_id, $jenjang_sekolah);            
            if (!$transaksi) {
                return [
                    'success' => false,
                    'message' => 'Peringkat tidak ditemukan'
                ];
            }
            return [
                'success' => true,
                'data' => PeringkatResource::collection($transaksi),
                'message' => 'Detail transaksi berhasil diambil',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail transaksi: ' . $e->getMessage()
            ];
        }
    }
}
