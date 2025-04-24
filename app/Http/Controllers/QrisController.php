<?php

namespace App\Http\Controllers;

use App\Http\Requests\Qris\CheckStatusRequest;
use App\Services\QrisService;
use App\Services\TagihanService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QrisController extends Controller
{
    use ApiResponse;

    public function __construct(
        private QrisService $qrisService,
        private TagihanService $tagihanService
    ) {}

    public function checkStatus(CheckStatusRequest $request): JsonResponse
    {
        // Get the QR data from the request
        $qrData = $request->validated('qr_data');
        
        // Get the tagihan from the service
        $tagihanResult = $this->tagihanService->getByQrData($qrData);
        dd($tagihanResult);
        // Debug the structure in a more readable way
        /*
        dd([
            'qr_data' => $qrData,
            'tagihan_result' => $tagihanResult,
            'tagihan_success' => $tagihanResult['success'] ?? null,
            'tagihan_data' => $tagihanResult['data'] ?? null,
            'tagihan_message' => $tagihanResult['message'] ?? null,
        ]);
        */
        
        if (!$tagihanResult['success']) {
            return $this->error($tagihanResult['message'], 404, null);
        }

        $result = $this->qrisService->checkStatus($tagihanResult['data']);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
