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

    public function getAll(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
           'end_date' => $request->end_date,
           'per_page' => $request->per_page,
           'sort_by' => $request->sort_by,
           'sort_direction' => $request->order_by,
           'is_read' => $request->is_read,
        ];

        $result = $this->pesanService->getAll($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200); 
    }

    public function getById($id)
    {
        $result = $this->pesanService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUser()
    {
        $result = $this->pesanService->getByUserId(Auth::user()->id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUserAndId($id)
    {
        $pesan = $this->pesanService->getById($id);
        if (!$pesan['success']) {
            return $this->error('Pesan tidak ditemukan', 404, null);
        }
        $result = $this->pesanService->update($pesan['data']->id, ['is_read' => true]);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($pesan['data'], $pesan['message'], 200);
    }

    public function getByUserId($userId)
    {
        $result = $this->pesanService->getByUserId($userId);
        if (!$result['success']) {
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
        if (!$result['success']) {
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
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function delete($id){
        $result = $this->pesanService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
