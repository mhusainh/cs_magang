<?php

namespace App\Http\Controllers;

use App\DTO\PekerjaanOrtuDTO;
use App\Http\Requests\PekerjaanOrtu\CreateRequest;
use App\Http\Requests\PekerjaanOrtu\UpdateRequest;
use App\Services\PekerjaanOrtuService;
use Illuminate\Http\JsonResponse;

class PekerjaanOrtuController extends Controller
{
    public function __construct(private PekerjaanOrtuService $service)
    {
    }

    public function getAll(): JsonResponse
    {
        $result = $this->service->getAll();
        return $this->response($result);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->service->getById($id);
        return $this->response($result);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = PekerjaanOrtuDTO::createPekerjaanOrtuDTO(
            $request->validated('nama_pekerjaan')
        );

        $result = $this->service->create($data);
        return $this->response($result, 201);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $data = PekerjaanOrtuDTO::updatePekerjaanOrtuDTO(
            $id,
            $request->validated('nama_pekerjaan')
        );

        $result = $this->service->update($data);
        return $this->response($result);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->service->delete($id);
        return $this->response($result);
    }
} 