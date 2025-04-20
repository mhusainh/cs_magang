<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PengajuanBiayaService;
use App\Http\Requests\PengajuanBiaya\CreateRequest;

class PengajuanBiayaController extends Controller
{
    use ApiResponse;

    public function __construct(private PengajuanBiayaService $pengajuanBiayaService) {}

    public function create(CreateRequest $request)
    {
        $data = [
            'nominal' => $request->validated('nominal'),
        ];
        $result = $this->pengajuanBiayaService->create($data);
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getAll()
    {
        $result = $this->pengajuanBiayaService->getAll();
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function update(CreateRequest $request, $id)
    {
        $data = [
            'nominal' => $request->validated('nominal'),
        ];
        $result = $this->pengajuanBiayaService->update($id, $data);
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete($id)
    {
        $result = $this->pengajuanBiayaService->delete($id);
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getById($id)
    {
        $result = $this->pengajuanBiayaService->getById($id);
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
