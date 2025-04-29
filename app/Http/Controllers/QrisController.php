<?php

namespace App\Http\Controllers;

use App\Http\Requests\Qris\CheckStatusRequest;
use App\Services\QrisService;
use App\Services\TagihanService;
use App\Services\TransaksiService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helpers\Logger;

class QrisController extends Controller
{
    use ApiResponse;

    public function __construct(
        private QrisService $qrisService,
        private TagihanService $tagihanService,
        private TransaksiService $transaksiService
    ) {}

    public function checkStatus(CheckStatusRequest $request): JsonResponse
    {
        $tagihanResult = $this->tagihanService->getByQrData($request->validated('qr_data'));
        if (!$tagihanResult['success']) {
            return $this->error($tagihanResult['message'], 404, null);
        }

        $result = $this->qrisService->checkStatus($tagihanResult['data']);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function webhookQris(Request $request): JsonResponse
    {
        $token = $request->query('token');
        if (empty($token)) {
            return response()->json([
                'responseCode' => '01',
                'responseMessage' => 'TOKEN INVALID',
                'responseTimestamp' => now()->format('Y-m-d H:i:s.u'),
                'transactionId' => $request->input('transactionId', '')
            ], 400);
        }

        $result = $this->qrisService->processWebhook($token, 'QRIS');
        $responseData = [
            'responseCode' => $result['success'] ? '00' : '01',
            'responseMessage' => $result['success'] ? 'TRANSACTION SUCCESS' : 'TRANSACTION FAILED',
            'responseTimestamp' => now()->format('Y-m-d H:i:s.u'),
            'transactionId' => $result['transactionId']
        ];
        Logger::log('webhook_qris', $result['requestData'], $responseData, null, $result['created_time']);
        return response()->json($responseData, $result['success'] ? 200 : 400);
    }
    public function webhookVaNumber(Request $request): JsonResponse
    {
        $token = $request->query('token');
        if (empty($token)) {
            return response()->json([
                'responseCode' => '01',
                'responseMessage' => 'TOKEN INVALID',
                'responseTimestamp' => now()->format('Y-m-d H:i:s.u'),
                'transactionId' => $request->input('transactionId', '')
            ], 400);
        }

        $result = $this->qrisService->processWebhook($token, 'VA_NUMBER');
        $responseData = [
            'responseCode' => $result['success'] ? '00' : '01',
            'responseMessage' => $result['success'] ? 'TRANSACTION SUCCESS' : 'TRANSACTION FAILED',
            'responseTimestamp' => now()->format('Y-m-d H:i:s.u'),
            'transactionId' => $result['transactionId']
        ];
        Logger::log('webhook_qris', $result['requestData'], $responseData, null, $result['created_time']);
        return response()->json($responseData, $result['success'] ? 200 : 400);
    }
}


