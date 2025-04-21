<?php

namespace App\Services;

use App\Http\Resources\Media\GetResource;
use App\Http\Resources\Media\HomepageResource;
use App\Repositories\MediaRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Support\Facades\Auth;

class MediaService
{
    public function __construct(
        private MediaRepository $mediaRepository
    ) {}

    public function create(array $data): array
    {
        try {
            $result = Cloudinary::upload($data['image']->getRealPath(), [
                'folder' => 'media',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    'compression' => 'low',
                ]
            ]);

            if (!$result || !$result->getPublicId() || !$result->getSecurePath()) {
                return [
                    'success' => false,
                    'message' => 'Gagal mengunggah gambar',
                ];
            }

            $dataMedia = [
                'nama' => $data['nama'],
                'public_id' => $result->getPublicId(),
                'url' => $result->getSecurePath(),
                'jenjang_sekolah' => $data['jenjang_sekolah'],
            ];

            $media = $this->mediaRepository->create($dataMedia);
            if (!$media) {
                Cloudinary::destroy($result->getPublicId());
                return [
                    'success' => false,
                    'message' => 'Gagal mengunggah gambar',
                ];
            }

            return [
                'success' => true,
                'data' => $media,
                'message' => 'Gambar berhasil diunggah',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat mengunggah gambar',
            ];
        }
    }

    public function getAll(array $filters = []): array
    {
        try {
            $media = $this->mediaRepository->getAll($filters);
            if (!$media || $media->isEmpty()) {
                return [
                    'success' => false,
                    'message' => 'Media tidak ditemukan',
                    'data' => null
                ];
            }

            $pagination = [
                'page' => $media->currentPage(),
                'per_page' => $media->perPage(),
                'total_items' => $media->total(),
                'total_pages' => $media->lastPage()
            ];

            $currentFilters = [
                'search' => $filters['search'] ?? '',
                'jenjang_sekolah' => $filters['jenjang_sekolah'] ?? '',
                'start_date' => $filters['start_date'] ?? '',
                'end_date' => $filters['end_date'] ?? '',
                'sort_by' => $filters['sort_by'] ?? '',
                'sort_direction' => $filters['sort_direction'] ?? ''
            ];

            return [
                'success' => true,
                'message' => 'Berhasil mendapatkan media',
                'data' => GetResource::collection($media),
                'pagination' => $pagination,
                'current_filters' => $currentFilters
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mendapatkan media: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function GetById($id): array
    {
        $media = $this->mediaRepository->findById($id);
        if (!$media) {
            return [
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ];
        }
        return [
            'success' => true,
            'data' => new GetResource($media),
            'message' => 'Berhasil mendapatkan gambar',
        ];
    }

    public function GetByUser($nama): array
    {
        $media = $this->mediaRepository->findByUser($nama, Auth::user()->peserta->jenjang_sekolah);
        if (!$media) {
            return [
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ];
        }
        return [
            'success' => true,
            'data' => new GetResource($media),
            'message' => 'Berhasil mendapatkan gambar',
        ];
    }

    public function update($image, $data, $id): array
    {
        try {
            $oldmedia = $this->mediaRepository->findById($id);
            if (!$oldmedia) {
                return [
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan',
                ];
            }
            
            $result = null;
            if ($image) {
                $result = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'media',
                    'transformation' => [
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                        'compression' => 'low',
                    ]
                ]);

                if (!$result || !$result->getPublicId() || !$result->getSecurePath()) {
                    return [
                        'success' => false,
                        'message' => 'Gagal mengunggah gambar',
                    ];
                }
            }

            // Update data di database
            $updated = $this->mediaRepository->update($result, $data, $id);
            if (!$updated) {
                // Jika update gagal dan ada gambar baru yang diupload, hapus gambar baru dari Cloudinary
                if ($image && isset($result)) {
                    Cloudinary::destroy($result->getPublicId());
                }
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui data',
                ];
            }

            // Hapus gambar lama dari Cloudinary hanya jika upload gambar baru berhasil
            if ($image && $oldmedia->public_id) {
                Cloudinary::destroy($oldmedia->public_id);
            }

            // Ambil data yang sudah diupdate untuk dikembalikan
            $updatedMedia = $this->mediaRepository->findById($id);
            if (!$updatedMedia) {
                return [
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan setelah diperbarui',
                ];
            }

            return [
                'success' => true,
                'message' => 'Data berhasil diperbarui',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat memperbarui gambar: ' . $e->getMessage(),
            ];
        }
    }

    public function delete($id): array
    {
        try {
            $media = $this->mediaRepository->findById($id);
            if (!$media) {
                return [
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan',
                ];
            }

            $deleted = $this->mediaRepository->delete($media);
            if (!$deleted) {
                return [
                    'success' => false,
                    'message' => 'Gagal menghapus gambar',
                ];
            }

            // Hapus gambar dari Cloudinary
            if ($media->public_id) {
                Cloudinary::destroy($media->public_id);
            }

            return [
                'success' => true,
                'message' => 'Gambar berhasil dihapus',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat menghapus gambar: ' . $e->getMessage(),
            ];
        }
    }
}
