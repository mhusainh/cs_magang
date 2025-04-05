<?php

namespace App\Http\Controllers;

use App\DTO\TransaksiDTO;
use App\Http\Requests\Transaksi\CreateRequest;
use App\Http\Requests\Transaksi\UpdateRequest;
use App\Services\TransaksiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function __construct(private TransaksiService $transaksiService)
    {
    }

    public function getAll(): JsonResponse
    {
        $result = $this->transaksiService->getAll(Auth::user()->id);
        return $this->response($result);
    }

    public function getById(int $id): JsonResponse
    {
        $userId = Auth::user()->id;
        $result = $this->transaksiService->getById($id, $userId);
        return $this->response($result);
    }

    
    public function delete(int $id): JsonResponse
    {
        $result = $this->transaksiService->delete($id);
        return $this->response($result);
    }
} 
        // public function create(CreateRequest $request): JsonResponse
        // {
        //     $userId = Auth::user()->id;
        //     $data = TransaksiDTO::createTransaksiDTO(
        //         $userId,
        //         $request->validated('tagihan_id'),
        //         $request->validated('total'),
        //         $request->validated('va_number'),
        //         $request->validated('transaction_qr_id'),
        //         $request->validated('method'),
        //         $request->validated('ref_no')
        //     );
    
        //     $result = $this->transaksiService->create($data);
        //     return $this->response($result, 201);
        // }
    
        // public function update(UpdateRequest $request, int $id): JsonResponse
        // {
        //     $data = TransaksiDTO::updateTransaksiDTO(
        //         $id,
        //         $request->validated('jumlah'),
        //         $request->validated('metode_pembayaran'),
        //         $request->validated('status'),
        //         $request->validated('waktu_transaksi')
        //     );
    
        //     $result = $this->transaksiService->update($data);
        //     return $this->response($result);
        // }