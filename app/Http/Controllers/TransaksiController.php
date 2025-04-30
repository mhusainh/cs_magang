<?php

namespace App\Http\Controllers;

use App\DTO\TransaksiDTO;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\TransaksiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Transaksi\CreateRequest;
use App\Http\Requests\Transaksi\UpdateRequest;

class TransaksiController extends Controller
{
    use ApiResponse;

    public function __construct(private TransaksiService $transaksiService) {}

    public function getAll(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'method' => $request->method,
            'total_min' => $request->total_min,
            'total_max' => $request->total_max,
            'per_page' => $request->per_page,
        ];

        $result = $this->transaksiService->getAll($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function getByUserId(int $userId, Request $request): JsonResponse
    {
        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $result = $this->transaksiService->getByUserId($userId, $filters);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->transaksiService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $userId = Auth::user()->id;
        $data = TransaksiDTO::createTransaksiDTO(
            $userId,
            $request->validated('total'),
            $request->validated('va_number'),
            $request->validated('transaction_qr_id'),
            $request->validated('method'),
            $request->validated('ref_no')
        );

        $result = $this->transaksiService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($data, $result['message'], 201);
    }

    public function update(UpdateRequest $request): JsonResponse
    {
        $data = TransaksiDTO::updateTransaksiDTO(
            $request->validated('id'),
            $request->validated('user_id'),
            $request->validated('tagihan_id'),
            $request->validated('status'),
            $request->validated('total'),
            $request->validated('va_number'),
            $request->validated('transaction_qr_id'),
            $request->validated('method'),
            $request->validated('ref_no')
        );

        $result = $this->transaksiService->update($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($data, $result['message'], 200);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->transaksiService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success(null, $result['message'], 200);
    }

    public function riwayat(Request $request): JsonResponse
    {
        $filters = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];

        $result = $this->transaksiService->getByUserId(Auth::user()->id, $filters);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getPeringkatByUser(): JsonResponse
    {
        $result = $this->transaksiService->getAllBookVee(Auth::user()->peserta->jurusan1_id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getPeringkat(Request $request): JsonResponse
    {
        $result = $this->transaksiService->getAllBookVee($request->jurusan_id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getDeleted(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'method' => $request->method,
            'total_min' => $request->total_min,
            'total_max' => $request->total_max,
            'per_page' => $request->per_page,
        ];

        $result = $this->transaksiService->getDeleted($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function restore(int $id): JsonResponse
    {
        $result = $this->transaksiService->restore($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success(null, $result['message'], 200);
    }
}
