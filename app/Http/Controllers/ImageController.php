<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\BeritaRequest;
use App\Http\Requests\Image\HomepageRequest;
use App\Traits\ApiResponse;
use App\Services\ImageService;
use Illuminate\Support\Facades\Auth;


class ImageController extends Controller
{
    use ApiResponse;
    public function __construct(private ImageService $imageService) {}

    public function uploadHomepage(HomepageRequest $request)
    {
        $uploadedImage = $request->validated('image');
        $urutan = $request->validated('urutan');
        $result = $this->imageService->createHomepage($uploadedImage, $urutan);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function uploadBerita(BeritaRequest $request)
    {
        $uploadedImage = $request->validated('image');
        $data = [
            'urutan' => $request->validated('urutan')
           
        ];

        $result = $this->imageService->createBerita($uploadedImage, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getAllHomepage()
    {
        $images = $this->imageService->getAllHomepage();
        if (!$images) {
            return $this->error('Gagal mendapatkan semua gambar homepage', 404, null);
        }

        return $this->success($images, 'Berhasil mendapatkan semua gambar homepage', 200);
    }

    public function getAllBerita()
    {
        $result = $this->imageService->getAllBerita();
        if (!$result['success']) {
            return $this->error('Gagal mendapatkan semua gambar berita', $result['code'], null);
        }

        return $this->success($result, 'Berhasil mendapatkan semua gambar berita', 200);
    }

    public function GetHomepageById($id)
    {
        $image = $this->imageService->GetHomepageById($id);
        if (!$image) {
            return $this->error('Gambar tidak ditemukan', 404, null);
        }

        return $this->success($image, 'Berhasil mendapatkan gambar', 200);
    }

    public function GetBeritaById($id)
    {
        $result = $this->imageService->GetBeritaById($id);
        if (!$result['success']) {
            return $this->error('Gagal mendapatkan gambar', $result['code'], null);
        }
        return $this->success($result, 'Berhasil mendapatkan gambar', 200);
    }

    public function updateHomepage(HomepageRequest $request, $id)
    {
        $uploadedImage = $request->validated('image');
        $urutan = $request->validated('urutan');
        $result = $this->imageService->updateHomepage($uploadedImage, $urutan, $id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function updateBerita(BeritaRequest $request, $id)
    {
        $uploadedImage = $request->validated('image');
        $data = [
            'urutan' => $request->validated('urutan'),
            'jenjang_sekolah' => $request->validated('jenjang_sekolah')
        ];

        $result = $this->imageService->updateBerita($uploadedImage, $data, $id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function deleteHomepage($id)
    {
        $result = $this->imageService->deleteHomepage($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function deleteBerita($id)
    {
        $result = $this->imageService->deleteBerita($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }
}
