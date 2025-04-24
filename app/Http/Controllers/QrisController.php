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
        $tagihan = $this->tagihanService->getByQrData($request->validated('qrData'));
        if (!$tagihan) {
            return $this->error('Tagihan not found', 404, null);
        }
        $result = $this->qrisService->checkStatus($tagihan['data']['qrData']);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
