<?php

namespace App\Http\Controllers;

use App\DTO\JurusanDTO;
use App\Http\Requests\Jurusan\CreateRequest;
use App\Http\Requests\Jurusan\UpdateRequest;
use App\Services\JurusanService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class JurusanController extends Controller
{
    use ApiResponse;

    public function __construct(private JurusanService $jurusanService) {}

    public function getAll(): JsonResponse
    {
        $result = $this->jurusanService->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result, $result['message'], 200);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->jurusanService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result, $result['message'], 200);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = JurusanDTO::createJurusanDTO(
            $request->validated('jurusan'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->jurusanService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result, $result['message'], 201);
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
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result, $result['message'], 201);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->jurusanService->delete($id);
        if  (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result, $result['message'], 204);
    }
}
