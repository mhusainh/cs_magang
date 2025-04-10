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

    public function create($uploadedImage, array $imagable): array
    {
        try {

            $cloudinary = Cloudinary::upload($uploadedImage->getRealPath());

            if (!$cloudinary ||!$cloudinary->getPublicId() ||!$cloudinary->getSecurePath()) {
             return [
                'success' => false,
                'message' => 'Gagal mengunggah gambar',
             ];   
            }

            $image = $this->imageRepository->create($uploadedImage, $cloudinary, $imagable);

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
}
