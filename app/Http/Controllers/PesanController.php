<?php

namespace App\Http\Controllers;

use App\DTO\PesanDTO;
use App\Http\Requests\Pesan\CreateRequest;
use App\Http\Requests\Pesan\UpdateRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PesanService;
use Illuminate\Support\Facades\Auth;

class PesanController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PesanService $pesanService
    ) {}

    public function getAll()
    {
        $result = $this->pesanService->getAll();
        if (!$result) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200); 
    }

    public function getById($id)
    {
        $result = $this->pesanService->getById($id);
        if (!$result) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUser()
    {
        $result = $this->pesanService->getByUserId(Auth::user()->id);
        if (!$result) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUserId($userId)
    {
        $result = $this->pesanService->getByUserId($userId);
        if (!$result) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function create(CreateRequest $request)
    {
        $data = PesanDTO::createPesanDTO(
            $request->validated('user_id'),
            $request->validated('judul'),
            $request->validated('deskripsi'),
        );
        $result = $this->pesanService->create($data);   
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function update($id, UpdateRequest $request){
        $data = PesanDTO::updatePesanDTO(
            $request->validated('judul'),
            $request->validated('deskripsi'),
        );
        $result = $this->pesanService->update($id, $data);
        if (!$result) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function delete($id){
        $result = $this->pesanService->delete($id);
        if (!$result) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
