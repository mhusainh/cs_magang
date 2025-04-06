<?php

namespace App\Http\Controllers;

use App\DTO\PekerjaanOrtuDTO;
use App\Http\Requests\PekerjaanOrtu\CreateRequest;
use App\Http\Requests\PekerjaanOrtu\UpdateRequest;
use App\Services\PekerjaanOrtuService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class PekerjaanOrtuController extends Controller
{
    use ApiResponse;

    public function __construct(private PekerjaanOrtuService $service) {}

    public function getAll(): JsonResponse
    {
        $result = $this->service->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result, $result['message'], 200);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->service->getById($id);

        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result, $result['message'], 200);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = PekerjaanOrtuDTO::createPekerjaanOrtuDTO(
            $request->validated('nama_pekerjaan')
        );

        $result = $this->service->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result, $result['message'], 201);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $data = PekerjaanOrtuDTO::updatePekerjaanOrtuDTO(
            $id,
            $request->validated('nama_pekerjaan')
        );

        $result = $this->service->update($data);

        if (!$result['success']) {
            return $this->error($result['message'], 422, null);  
        }
        return $this->success($result, $result['message'], 201);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->service->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null); 
        }
        return $this->success($result, $result['message'], 204);
    }
}
