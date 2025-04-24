<?php

namespace App\Http\Controllers;

use App\DTO\TransaksiDTO;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Transaksi\CreateRequest;
use App\Http\Requests\Transaksi\UpdateRequest;

class TransaksiController extends Controller
{
    use ApiResponse;

    public function __construct(private TransaksiService $transaksiService) {}

    public function getAll(): JsonResponse
    {
        $result = $this->transaksiService->getAll(Auth::user()->id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getById(int $id): JsonResponse
    {
        $userId = Auth::user()->id;
        $result = $this->transaksiService->getById($id, $userId);
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

    public function riwayat(): JsonResponse
    {
        $result = $this->transaksiService->getByUserId(Auth::user()->id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getPeringkat(): JsonResponse
    {
        $result = $this->transaksiService->getAllBookVee();
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200, $result['pagination']);
    }
}
