<?php

namespace App\Http\Controllers;

use App\DTO\ImageDTO;
use App\Http\Requests\Image\BeritaRequest;
use App\Http\Requests\Image\HomepageRequest;
use App\Models\Image;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Image\CreateRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ImageController extends Controller
{
    use ApiResponse;
    public function __construct(private ImageService $imageService) {}

    public function uploadHomepage(HomepageRequest $request)
    {
        $uploadedImage = $request->validated('image');
        $urutan = $request->validated('urutan');
        $result = $this->imageService->create_homepage($uploadedImage, $urutan);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function uploadBerita(BeritaRequest $request)
    {
        $uploadedImage = $request->validated('image');
        $data = [
            'urutan' => $request->validated('urutan'),
            'jenjang_sekolah' => $request->validated('jenjang_sekolah')
        ];
        
        $result = $this->imageService->create_berita($uploadedImage, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
