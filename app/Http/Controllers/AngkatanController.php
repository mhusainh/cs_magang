<?php

namespace App\Http\Controllers;

use App\Http\Requests\Angkatan\InputRequest;
use App\Services\AngkatanService;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponse;

class AngkatanController extends Controller
{
    use ApiResponse;

    public function __construct(private AngkatanService $service) {}

    public function getAll(): JsonResponse
    {
        $result = $this->service->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function create(InputRequest $request): JsonResponse
    {
        $result = $this->service->create($request->validated('angkatan'));
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function update(InputRequest $request, int $id): JsonResponse
    {
        $data = [
            $id,
            $request->validated('angkatan')
        ];

        $result = $this->service->update($data);

        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->service->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success(null, $result['message'], 200);
    }
}
