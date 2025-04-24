<?php

namespace App\Http\Controllers;

use App\Http\Requests\Media\CreateRequest;
use App\Http\Requests\Media\UpdateRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\MediaService;
use Illuminate\Http\JsonResponse;

class MediaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private MediaService $mediaService
    ) {}

    public function GetAll(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'jenjang_sekolah' => $request->jenjang_sekolah,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'per_page' => $request->per_page
        ];

        $result = $this->mediaService->GetAll($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function GetById(int $id): JsonResponse
    {
        $result = $this->mediaService->GetById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function GetByUser(string $nama): JsonResponse
    {
        $result = $this->mediaService->GetByUser($nama);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function uploadJadwal(CreateRequest $request): JsonResponse
    {
        $image = $request->validated('image');
        $data = [
            'nama' => 'jadwal',
            'image' => $image,
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
        ];
        $result = $this->mediaService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function uploadPengajuanBiaya(CreateRequest $request): JsonResponse
    {
        $image = $request->validated('image');
        $data = [
            'nama' => 'pengajuan_biaya',
            'image' => $image,
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
        ];
        $result = $this->mediaService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }


    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $image = $request->validated('image');
        $data = [
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
        ];
        $result = $this->mediaService->update($image, $data, $id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success(null, $result['message'], 200);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->mediaService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success(null, $result['message'], 200);
    }
}
