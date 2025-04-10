<?php

namespace App\Services;

use App\DTO\ImageDTO;
use App\Http\Requests\Image\CreateRequest;
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
        ];}
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
        ];}
    }

    public function getAllHomepage(): array
    {
        $images = $this->imageRepository->getHomepage();
        return [
            'success' => true,
            'data' => HomepageResource::collection($images),
            'message' => 'Berhasil mendapatkan semua gambar homepage',
        ];
    }

    public function getAllBerita($jenjang): array
    {
        $images = $this->imageRepository->getBerita($jenjang);
        return [
           'success' => true,
            'data' => HomepageResource::collection($images),
           'message' => 'Berhasil mendapatkan semua gambar berita',
        ];
    }
}
