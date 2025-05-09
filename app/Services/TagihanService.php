<?php

namespace App\Services;

use App\Repositories\TagihanRepository;

class TagihanService
{
    public function __construct(
        private TagihanRepository $tagihanRepository,
        private QrisService $qrisService
    ) {}

    public function getById(int $id): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($id);

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
            $data['total'] = str_replace('-', '', $data['total']);
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

    public function getAll($filters = []): array
    {
        try {
            $tagihans = $this->tagihanRepository->getAll($filters);
            if($tagihans->isEmpty()) {
                return [
                   'success' => false,
                   'message' => 'Tagihan tidak ditemukan'
                ];
            }

            $pagination = [
              'page' => $tagihans->currentPage(),
              'per_page' => $tagihans->perPage(),
              'total_items' => $tagihans->total(),
              'total_pages' => $tagihans->lastPage(),
            ];

            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'start_date' => $filters['start_date']?? '',
               'end_date' => $filters['end_date']?? '',
               'status' => $filters['status']?? '',
               'sort_by' => $filters['sort_by']?? '',
               'sort_direction' => $filters['sort_direction']?? '',
               'nama_tagihan' => $filters['nama_tagihan']?? '',
               'total_min' => $filters['total_min']?? '',
               'total_max' => $filters['total_max']?? '',
            ];
            return [
                'success' => true,
                'data' => $tagihans,
                'pagination' => $pagination,
                'current_filters' => $currentFilters,
                'message' => 'Tagihan user berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function getByUserId (int $userId): array
    {
        try {
            $tagihans = $this->tagihanRepository->getByUserId($userId);
            if (!$tagihans) {
                return [
                   'success' => false,
                   'message' => 'Tagihan tidak ditemukan'
                ];
            }
            return [
               'success' => true,
                'data' => $tagihans,
               'message' => 'Tagihan user berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
               'success' => false,
               'message' => 'Gagal mengambil tagihan: '. $e->getMessage()
            ];
        }
    }

    public function getByQrData(string $qrData): array
    {
        try {
            // Get the tagihan from the repository
            $tagihan = $this->tagihanRepository->getByQrData($qrData);

            // Debug the raw data from repository if needed
            // dd(['repository_returns' => $tagihan]);

            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            // Convert the Eloquent model to an array
            $tagihanArray = $tagihan->toArray();

            // Make sure we have all the required fields for QRIS check
            if (!isset($tagihanArray['total']) || !isset($tagihanArray['created_time']) || !isset($tagihanArray['transaction_qr_id'])) {
                // Debug the missing fields
                // dd(['missing_fields' => true, 'tagihan_data' => $tagihanArray]);

                return [
                    'success' => false,
                    'message' => 'Data tagihan tidak lengkap untuk QRIS'
                ];
            }

            return [
                'success' => true,
                'data' => $tagihanArray,
                'message' => 'Tagihan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function getByVaNumber(string $qrData): array
    {
        try {
            // Get the tagihan from the repository
            $tagihan = $this->tagihanRepository->getByVaNumber($qrData);

            // Debug the raw data from repository if needed
            // dd(['repository_returns' => $tagihan]);

            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            // Convert the Eloquent model to an array
            $tagihanArray = $tagihan->toArray();

            // Make sure we have all the required fields for VA Number check
            if (!isset($tagihanArray['total']) || !isset($tagihanArray['created_time'])) {
                // Debug the missing fields
                // dd(['missing_fields' => true, 'tagihan_data' => $tagihanArray]);

                return [
                    'success' => false,
                    'message' => 'Data tagihan tidak lengkap untuk VA Number'
                ];
            }

            return [
                'success' => true,
                'data' => $tagihanArray,
                'message' => 'Tagihan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function getDeleted($filters = []): array
    {
        try {
            $tagihans = $this->tagihanRepository->getTrash($filters);
            if($tagihans->isEmpty()) {
                return [
                   'success' => false,
                   'message' => 'Tagihan tidak ditemukan'
                ];
            }

            $pagination = [
              'page' => $tagihans->currentPage(),
              'per_page' => $tagihans->perPage(),
              'total_items' => $tagihans->total(),
              'total_pages' => $tagihans->lastPage(),
            ];

            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'start_date' => $filters['start_date']?? '',
               'end_date' => $filters['end_date']?? '',
               'status' => $filters['status']?? '',
               'sort_by' => $filters['sort_by']?? '',
               'sort_direction' => $filters['sort_direction']?? '',
               'nama_tagihan' => $filters['nama_tagihan']?? '',
               'total_min' => $filters['total_min']?? '',
               'total_max' => $filters['total_max']?? '',
            ];
            return [
                'success' => true,
                'data' => $tagihans,
                'pagination' => $pagination,
                'current_filters' => $currentFilters,
                'message' => 'Tagihan user berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function restore(int $id): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($id);

            if (!$tagihan) {
                return [
                   'success' => false,
                   'message' => 'Tagihan tidak ditemukan'
                ];
            }

            $result = $this->tagihanRepository->restore($tagihan);
            if (!$result) {
                return [
                 'success' => false,
                 'message' => 'Gagal memulihkan tagihan'
                ];
            }

            return [
               'success' => true,
               'message' => 'Tagihan berhasil dipulihkan'
            ];
        } catch (\Exception $e) {
            return [
              'success' => false,
              'data' => $tagihan,
              'message' => 'Gagal memulihkan tagihan: '. $e->getMessage()
            ];
        }
    }
}
