<?php

namespace App\Services;

use App\Repositories\BerkasRepository;
use App\Services\KetentuanBerkasService;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class BerkasService
{
    public function __construct(
        private BerkasRepository $berkasRepository,
        private KetentuanBerkasService $ketentuanRepository,
        private KetentuanBerkasService $ketentuanBerkasService
    ) {}

    public function uploadBerkas($file, $pesertaId, $ketentuanBerkasId)
    {
        try {
            // Cek apakah ketentuan berkas ada
            $ketentuanBerkas = $this->ketentuanRepository->getKetentuanBerkasById($ketentuanBerkasId);
            if (!$ketentuanBerkas) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            // Cek apakah berkas sudah pernah diupload
            $existingBerkas = $this->berkasRepository->getBerkasByPesertaAndKetentuanId($pesertaId, $ketentuanBerkasId);
            // Jika sudah ada, hapus berkas lama dari Cloudinary
            if ($existingBerkas) {
                if ($existingBerkas->public_id) {
                    Cloudinary::destroy($existingBerkas->public_id);
                }
                $existingBerkas->delete();
            }

            // Upload berkas ke Cloudinary
            $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'berkas',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'compression' => 'low',
                ]
            ]);

            // Simpan data berkas ke database
            $berkas = [
                'peserta_id' => $pesertaId,
                'kententuan_berkas_id' => $ketentuanBerkasId,
                'url_file' => $uploadedFile->getSecurePath(),
                'public_id' => $uploadedFile->getPublicId()
            ];

            $result= $this->berkasRepository->createBerkas($berkas);

            return [
                'success' => true,
                'message' => 'Berhasil mengupload berkas',
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupload berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getBerkasByPesertaId($pesertaId)
    {
        try {
            // Menggunakan repository untuk mendapatkan berkas
            $berkasQuery = $this->berkasRepository->getBerkasByPesertaId($pesertaId);
            $berkas = $berkasQuery->with('ketentuanBerkas')->get();

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan berkas',
                'data' => $berkas
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

    public function updateBerkas($id, $file, $ketentuanBerkasId)
    {
        try {
            // Cek apakah berkas ada menggunakan repository
            $berkas = $this->berkasRepository->getBerkasById($id);
            if (!$berkas) {
                return [
                    'success' => false,
                    'message' => 'Berkas tidak ditemukan',
                    'data' => null
                ];
            }

            // Cek apakah ketentuan berkas ada menggunakan repository
            $ketentuanBerkas = $this->ketentuanRepository->getKetentuanBerkasById($ketentuanBerkasId);
            if (!$ketentuanBerkas) {
                return [
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan',
                    'data' => null
                ];
            }

            // Hapus berkas lama dari Cloudinary
            if ($berkas->public_id) {
                Cloudinary::destroy($berkas->public_id);
            }

            // Upload berkas baru ke Cloudinary
            $uploadedFile = Cloudinary::upload($file->getRealPath(), [
                'folder' => 'berkas',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'compression' => 'low',
                ]
            ]);

            // Update data berkas di database
            $berkas=[
                'kententuan_berkas_id' => $ketentuanBerkasId,
                'url_file' => $uploadedFile->getSecurePath(),
                'public_id' => $uploadedFile->getPublicId()
            ];

            $result = $this->berkasRepository->updateBerkas($berkas);
            if (!$result) {
                return [
                   'success' => false,
                   'message' => 'Gagal mengupdate berkas',
                    'data' => null
                ];
            }
            return [
                'success' => true,
                'message' => 'Berhasil mengupdate berkas',
                'data' => $berkas
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengupdate berkas: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}