<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\BiayaPendaftaranService;
use App\Http\Requests\BiayaPendaftaran\CreateRequest;

class BiayaPendaftaranController extends Controller
{
    use ApiResponse;

    public function __construct(private BiayaPendaftaranService $biayaPendaftaranService) {}

    public function create(CreateRequest $request)
    {
        $data = [
            'data' => $request->validated('nominal'),
        ];
        dd($data);
        $result = $this->biayaPendaftaranService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function getAll()
    {
        $result = $this->biayaPendaftaranService->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function update(CreateRequest $request, $id)
    {
        $data = [
            $request->validated('nominal'),
        ];
        $result = $this->biayaPendaftaranService->update($id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete($id)
    {
        $result = $this->biayaPendaftaranService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getById($id)
    {
        $result = $this->biayaPendaftaranService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
