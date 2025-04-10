<?php

namespace App\Services;

use App\DTO\ImageDTO;
use App\Http\Requests\Image\CreateRequest;
use App\Models\Image;
use App\Repositories\ImageRepository;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;

class ImageService
{
    public function __construct(
        private ImageRepository $imageRepository
    ) {}

    public function create_homepage($image, int $urutan): array
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
            

            $image = $this->imageRepository->create_homepage($result, $urutan);
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

    public function create_berita($image, $data): array
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
            

            $image = $this->imageRepository->create_berita($result, $data);
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
}
