<?php

namespace App\Http\Controllers;

use App\DTO\PesertaDTO;
use App\Services\PesertaService;
use App\Traits\ApiResponse;
use App\Http\Requests\Peserta\CreatePesertaRequest;
use App\Http\Requests\Peserta\UpdatePesertaRequest;

class PesertaController extends Controller
{
    use ApiResponse;

    public function __construct(private PesertaService $pesertaService)
    {
    }

    public function create(CreatePesertaRequest $request)
    {
        $data = PesertaDTO::createPesertaDTO(
            $request->validated('user_id'),
            $request->validated('nama'),
            $request->validated('no_telp'),
            $request->validated('jenis_kelamin'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->pesertaService->create($data);
        
        if (!$result['success']) {
            return $this->error($result['message'], 422);
        }

        return $this->success($result['data'], $result['message'], 201);
    }

    public function getById(int $id)
    {
        $result = $this->pesertaService->getById($id);
        
        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($result['data']);
    }

    public function getByUserId(int $userId)
    {
        $result = $this->pesertaService->getByUserId($userId);
        
        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($result['data']);
    }

    public function update(UpdatePesertaRequest $request, int $id)
    {
        $data = PesertaDTO::createPesertaDTO(
            $request->validated('user_id'),
            $request->validated('nama'),
            $request->validated('no_telp'),
            $request->validated('jenis_kelamin'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->pesertaService->update($id, $data);
        
        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($result['data'], $result['message']);
    }

    public function delete(int $id)
    {
        $result = $this->pesertaService->delete($id);
        
        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success(null, $result['message']);
    }

    public function getAll()
    {
        $result = $this->pesertaService->getAll();
        
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data']);
    }
} 