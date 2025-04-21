<?php

namespace App\Services;

use App\DTO\ImageDTO;
use App\Http\Requests\Image\CreateRequest;
use App\Http\Resources\Image\BeritaResource;
use App\Http\Resources\Image\HomepageResource;
use App\Models\Image;
use App\Repositories\ImageRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ImageService
{
    public function __construct(
        private ImageRepository $imageRepository
    ) {}

    public function createHomepage($image, int $urutan): array
    {
        try {
            $result = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'homepage',
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


            $image = $this->imageRepository->createHomepage($result, $urutan);
            if (!$image) {
                Cloudinary::destroy($result->getPublicId());
                return [
                    'success' => false,
                    'message' => 'Gagal mengunggah gambar',
                ];
            }


            return [
                'success' => true,
                'data' => $image,
                'message' => 'Gambar berhasil diunggah',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat mengunggah gambar',
            ];
        }
    }

    public function createBerita($image, $data): array
    {
        try {
            $result = Cloudinary::upload($image->getRealPath(), [
                'folder' => 'berita',
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


            $image = $this->imageRepository->createBerita($result, $data);
            if (!$image) {
                Cloudinary::destroy($result->getPublicId());
                return [
                    'success' => false,
                    'message' => 'Gagal mengunggah gambar',
                ];
            }
            return [
                'success' => true,
                'data' => $image,
                'message' => 'Gambar berhasil diunggah',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat mengunggah gambar',
            ];
        }
    }

    public function getAllHomepage(): array
    {
        $images = $this->imageRepository->getHomepage();
        if (!$images) {
            return [
               'success' => false,
               'message' => 'Gagal mendapatkan semua gambar homepage',
            ]; 
        }
        return [
            'success' => true,
            'data' => HomepageResource::collection($images),
            'message' => 'Berhasil mendapatkan semua gambar homepage',
        ];
    }

    public function getAllBerita(): array
    {
        try {
        $images = $this->imageRepository->getBerita();
        if (!$images) {
            return [
                'code' => 200,
                'success' => false,
                'message' => 'Gagal mendapatkan semua gambar berita',
            ]; 
        }
        return [
            'code' => 200,
           'success' => true,
            'data' => BeritaResource::collection($images),
           'message' => 'Berhasil mendapatkan semua gambar berita',
        ];} catch (\Exception $e) {
            return [
                'code' => 400,
               'success' => false,
               'message' => 'Gagal mendapatkan semua gambar berita',
            ];
        }      
    }

    public function getBeritaByUser($jenjang): array
    {
        $images = $this->imageRepository->getBeritaByUser($jenjang);
        if (!$images) {
            return [
             'success' => false,
             'message' => 'Gagal mendapatkan semua gambar berita',
            ]; 
        }
        return [
            'success' => true,
            'data' => BeritaResource::collection($images),
            'message' => 'Berhasil mendapatkan semua gambar berita',
        ];
    }

    public function GetHomepageById($id): array
    {
        $image = $this->imageRepository->findHomepageById($id);
        if (!$image) {
            return [
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ];
        }
        return [
            'success' => true,
            'data' => new HomepageResource($image),
            'message' => 'Berhasil mendapatkan gambar',
        ];
    }

    public function GetBeritaById($id): array
    {
        try {
        $image = $this->imageRepository->findBeritaById($id);
            if (!$image) {
                return [
                    'code' => 200,
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan',
                ];
            }
            return [
                'code' => 200,
                'success' => true,
                'data' => new BeritaResource($image),
                'message' => 'Berhasil mendapatkan gambar',
            ];
        } catch (\Exception $e) {
            return [
                'code' => 400,
                'success' => false,
                'message' => 'Gagal mendapatkan gambar',
            ];
        }
    }

    public function updateHomepage($image, $data, $id): array
    {
        try {
            $oldImage = $this->imageRepository->findHomepageById($id);
            if (!$oldImage) {
                return [
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan',
                ];
            }

            $result = null;
            if ($image) {
                $result = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'homepage',
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

            $updated = $this->imageRepository->updateHomepage(
                $image ? $result : null,
                $data,
                $id
            );

            if (!$updated) {
                if ($image && isset($result)) {
                    Cloudinary::destroy($result->getPublicId());
                }
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui gambar',
                ];
            }

            // Hapus gambar lama hanya jika update berhasil dan ada gambar baru
            if ($image && isset($result)) {
                Cloudinary::destroy($oldImage->public_id);
            }

            $updatedImage = $this->imageRepository->findHomepageById($id);
            return [
                'success' => true,
                'data' => new HomepageResource($updatedImage),
                'message' => 'Gambar berhasil diperbarui',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat memperbarui gambar',
            ];
        }
    }

    public function updateBerita($image, $data, $id): array
    {
        try {
            $oldImage = $this->imageRepository->findBeritaById($id);
            if (!$oldImage) {
                return [
                    'success' => false,
                    'message' => 'Gambar tidak ditemukan',
                ];
            }

            if ($image) {
                $result = Cloudinary::upload($image->getRealPath(), [
                    'folder' => 'berita',
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
                Cloudinary::destroy($oldImage->public_id);
            }

            $updated = $this->imageRepository->updateBerita(
                $image ? $result : null,
                $data,
                $id
            );

            if (!$updated) {
                if ($image && isset($result)) {
                    Cloudinary::destroy($result->getPublicId());
                }
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui gambar',
                ];
            }
            $updatedImage = $this->imageRepository->findBeritaById($id);
            return [
                'success' => true,
                'message' => 'Gambar berhasil diperbarui',
                'data' => new BeritaResource($updatedImage),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Kesalahan saat memperbarui gambar',
            ];
        }
    }

    public function deleteHomepage($id): array
    {
        $image = $this->imageRepository->findHomepageById($id);
        if (!$image) {
            return [
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ];
        }

        $deleted = $this->imageRepository->deleteHomepage($id)
            && Cloudinary::destroy($image->public_id);

        if (!$deleted) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus gambar',
            ];
        }

        return [
            'success' => true,
            'message' => 'Gambar berhasil dihapus',
        ];
    }

    public function deleteBerita($id): array
    {
        $image = $this->imageRepository->findBeritaById($id);
        if (!$image) {
            return [
                'success' => false,
                'message' => 'Gambar tidak ditemukan',
            ];
        }

        $deleted = $this->imageRepository->deleteBerita($id)
            && Cloudinary::destroy($image->public_id);
        if (!$deleted) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus gambar',
            ];
        }
        return [
            'success' => true,
            'message' => 'Gambar berhasil dihapus',
        ];
    }
}
