<?php

namespace App\Http\Controllers;

use App\DTO\JurusanDTO;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use App\Services\JurusanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Jurusan\CreateRequest;
use App\Http\Requests\Jurusan\UpdateRequest;
use App\Http\Resources\Jurusan\GetUniqueJenjangSekolahResource;

class JurusanController extends Controller
{
    use ApiResponse;

    public function __construct(private JurusanService $jurusanService) {}

    public function getAll(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'jenjang' => $request->jenjang_sekolah,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'per_page' => $request->per_page
        ];

        $result = $this->jurusanService->getAll($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->jurusanService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = JurusanDTO::createJurusanDTO(
            $request->validated('jurusan'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->jurusanService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $data = JurusanDTO::updateJurusanDTO(
            $id,
            $request->validated('jurusan'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->jurusanService->update($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->jurusanService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success(null, $result['message'], 200);
    }

    public function getByJenjang(): JsonResponse
    {
        $result = $this->jurusanService->getJurusanByJenjang(Auth::user()->peserta->jenjang_sekolah);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getUniqueJenjang(): JsonResponse
    {
        $result = $this->jurusanService->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }

        $uniqueJenjang = GetUniqueJenjangSekolahResource::collection($result['data']);
        return $this->success($uniqueJenjang, 'Daftar jenjang sekolah berhasil diambil', 200);
    }

    public function getDeleted(): JsonResponse
    {
        $result = $this->jurusanService->getDeleted();
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function restore(int $id): JsonResponse
    {
        $result = $this->jurusanService->restore($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success(null, $result['message'], 200);
    }
}
