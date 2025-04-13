<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Repositories\BerkasRepository;
use App\Services\KetentuanBerkasService;
use App\Repositories\KetentuanBerkasRepository;
use App\Http\Resources\Berkas\GetAllBerkasResource;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class BerkasService
{
    public function __construct(
        private BerkasRepository $berkasRepository,
        private KetentuanBerkasRepository $KetentuanBerkasRepository,
        private KetentuanBerkasService $ketentuanBerkasService
    ) {}

    public function uploadBerkas($data)
    {
        try {
            // Cek apakah ketentuan berkas ada menggunakan repository
            $ketentuanBerkas = $this->KetentuanBerkasRepository->getKetentuanBerkasById($data['ketentuan_berkas_id']);
            if (!$ketentuanBerkas) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            // Cek apakah berkas sudah ada
            $existingBerkas = $this->berkasRepository->getBerkasByPesertaAndKetentuanId(
                $data['peserta_id'],
                $data['ketentuan_berkas_id']
            );

            // Jika berkas sudah ada, hapus berkas lama dari Cloudinary
            if ($existingBerkas && $existingBerkas->public_id) {
                Cloudinary::destroy($existingBerkas->public_id);
                $this->berkasRepository->deleteBerkas($existingBerkas->id);
            }

            // Upload berkas ke Cloudinary
            $uploadedFile = Cloudinary::upload($data['file']->getRealPath(), [
                'folder' => 'berkas',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'compression' => 'low',
                ]
            ]);

            $berkas = [
                'peserta_id' => $data['peserta_id'],
                'nama_file' => $ketentuanBerkas->nama,
                'url_file' => $uploadedFile->getSecurePath(),
                'public_id' => $uploadedFile->getPublicId(),
                'ketentuan_berkas_id' => $data['ketentuan_berkas_id']
            ];

            // Simpan data berkas ke database dalam transaction
            DB::beginTransaction();
            try {
                $result = $this->berkasRepository->createBerkas($berkas);
                if (!$result) {
                    throw new \Exception('Gagal menyimpan data berkas');
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                Cloudinary::destroy($uploadedFile->getPublicId());
                return [
                    'success' => false,
                    'message' => 'Gagal mengupload berkas: ' . $e->getMessage(),
                    'data' => null
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupload berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getAllBerkas($filters = [])
    {
        try {
            // Get berkas with filters and pagination
            $berkas = $this->berkasRepository->getAllBerkas($filters);
            if (!$berkas) {
                return [
                    'success' => false,
                    'message' => 'Berkas tidak ditemukan',
                    'data' => null
                ];
            }

            // Get total items for current filter
            $totalItems = $berkas->total();
            $totalpages = $berkas->lastPage(); // Jumlah halaman total
            $currentPage = $berkas->currentPage(); // Halaman saat ini
            $perPage = $berkas->perPage(); // Jumlah item per halam

            // Get current filter status
            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'ketentuan_berkas_id' => $filters['ketentuan_berkas_id'] ?? '',
                'start_date' => $filters['start_date'] ?? '',
                'end_date' => $filters['end_date'] ?? '',
                'jenjang_sekolah' => $filters['jenjang_sekolah'] ?? '',
                'nama_ketentuan' => $filters['nama_ketentuan'] ?? '',
                'is_required' => $filters['is_required'] ?? '',
                'sort_by' => $filters['sort_by']?? '',
                'sort_direction' => $filters['sort_direction']?? ''
            ];

            // Set pagination data
            $pagination = [
                'page' => $currentPage,
                'per_page' => $perPage,
                'total_items' => $totalItems,
                'total_pages' => $totalpages
            ];

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan berkas',
                'data' => [
                    'berkas' => GetAllBerkasResource::collection($berkas),
                    'current_filters' => $currentFilters
                ],
                'pagination' => $pagination
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getStatusBerkasPeserta($pesertaId, $jenjangSekolah)
    {
        try {
            // Ambil semua ketentuan berkas untuk jenjang sekolah ini dari KetentuanBerkasService
            $ketentuanBerkasResult = $this->ketentuanBerkasService->getKetentuanBerkasByJenjang($jenjangSekolah);
            if (!$ketentuanBerkasResult['success']) {
                return $ketentuanBerkasResult;
            }

            $ketentuanBerkas = $ketentuanBerkasResult['data'];

            $result = [];
            foreach ($ketentuanBerkas as $ketentuan) {
                // Cek apakah berkas sudah diupload menggunakan repository
                $berkas = $this->berkasRepository->getBerkasByPesertaAndKetentuanId($pesertaId, $ketentuan->id);

                $result[] = [
                    'ketentuan_id' => $ketentuan->id,
                    'nama' => $ketentuan->nama,
                    'is_required' => $ketentuan->is_required,
                    'is_uploaded' => $berkas ? true : false,
                    'berkas' => $berkas
                ];
            }

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan status berkas',
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan status berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function deleteBerkas($id)
    {
        try {
            // Menggunakan repository untuk mendapatkan berkas berdasarkan ID
            $berkas = $this->berkasRepository->getBerkasById($id);
            if (!$berkas) {
                return [
                    'success' => false,
                    'message' => 'Berkas tidak ditemukan',
                    'data' => null
                ];
            }

            // Hapus berkas dari Cloudinary
            if ($berkas->public_id) {
                Cloudinary::destroy($berkas->public_id);
            }

            // Menggunakan repository untuk menghapus berkas
            $result = $this->berkasRepository->deleteBerkas($id);
            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus berkas',
                    'data' => null
                ];
            }
            return [
                'success' => true,
                'message' => 'Berhasil menghapus berkas',
                'data' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
