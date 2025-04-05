<?php

namespace App\Http\Controllers;

use App\DTO\JurusanDTO;
use App\Http\Requests\Jurusan\CreateRequest;
use App\Http\Requests\Jurusan\UpdateRequest;
use App\Services\JurusanService;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\Jurusan\GetAllResource;
use App\Http\Resources\Jurusan\getDetailResource;

class JurusanController extends Controller
{
    public function __construct(private JurusanService $jurusanService)
    {
    }

    public function getAll(): JsonResponse
    {
        $result = $this->jurusanService->getAll();
        return new GetAllResource($result['data']);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->jurusanService->getById($id);
        return new getDetailResource($result['data']);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = JurusanDTO::createJurusanDTO(
            $request->validated('jurusan'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->jurusanService->create($data);
        return $this->response($result, 201);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $data = JurusanDTO::updateJurusanDTO(
            $id,
            $request->validated('jurusan'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->jurusanService->update($data);
        return $this->response($result);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->jurusanService->delete($id);
        return $this->response($result);
    }
} 