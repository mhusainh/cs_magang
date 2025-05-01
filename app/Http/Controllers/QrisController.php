<?php

namespace App\Http\Controllers;

use App\Http\Requests\Qris\CheckStatusRequest;
use App\Services\QrisService;
use App\Services\TagihanService;
use App\Services\TransaksiService;
use App\Services\PesanService;
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
        private TransaksiService $transaksiService,
        private PesanService $pesanService,
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
            'transactionId' => $result['transactionId'],
        ];
        Logger::log('webhook_qris', $result['requestData'], $responseData, null, $result['transactionId']);
        $dataPesan = [
            'user_id' => $result['userId'],
            'judul' => 'Pembayaran',
            'deskripsi' => $responseData['responseMessage'] === 'TRANSACTION SUCCESS' ?  'Halo ' . $result['namaPeserta']. ', Traksaksi sebesar Rp.' . $result['total'] . 'melalui Qris telah berhasil. Terima kasih!' 
                            : 'Halo '. $result['namaPeserta']. ', Traksaksi sebesar Rp.'. $result['total'].'melalui Qris telah gagal. Terima kasih!',
        ];

        $pesan = $this->pesanService->create($dataPesan);
        
        if (!$pesan['success']) {
            // Tetap kembalikan sukses meskipun pesan gagal dibuat
            return $this->success($result, 'Semua file berhasil diupload, tetapi notifikasi gagal dibuat', 200);
        }
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
        Logger::log('webhook_qris', $result['requestData'], $responseData, null, $result['transactionId']);
        $dataPesan = [
            'user_id' => $result['userId'],
            'judul' => 'Pembayaran',
            'deskripsi' => $responseData['responseMessage'] === 'TRANSACTION SUCCESS' ? 'Halo ' . $result['namaPeserta']. ', Traksaksi sebesar Rp.' . $result['total'] . 'melalui Virtual Akun telah berhasil. Terima kasih!' 
                            : 'Halo '. $result['namaPeserta']. ', Traksaksi sebesar Rp.'. $result['total'].'melalui Virtual Akun telah gagal. Terima kasih!',
        ];

        $pesan = $this->pesanService->create($dataPesan);
        
        if (!$pesan['success']) {
            // Tetap kembalikan sukses meskipun pesan gagal dibuat
            return $this->success($result, 'Semua file berhasil diupload, tetapi notifikasi gagal dibuat', 200);
        }
        return response()->json($responseData, $result['success'] ? 200 : 400);
    }
}


