<?php

namespace App\Http\Controllers;

use App\Helpers\JWT;
use App\Helpers\Logger;
use App\Services\VaService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private VaService $vaService
    ) {}

    /**
     * Menangani permintaan inquiry VA
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function inquiry(Request $request): JsonResponse
    {
        try {
            $token = $request->query('token');
            if (empty($token)) {
                return response()->json([
                    'responseCode' => '01',
                    'responseMessage' => 'TOKEN INVALID',
                    'responseTimestamp' => now()->format('Y-m-d H:i:s.u'),
                    'transactionId' => $request->input('transactionId', '')
                ], 400);
            }
            try {
                $decodedToken = JWT::decode($token, $_ENV['QRIS_JWT_SECRET'], ['HS256']);
            } catch (\Exception $e) {
                Logger::log('webhook_qris', ['token' => $token], null, 'Token tidak valid: ' . $e->getMessage(), time());
                return ['success' => false, 'message' => 'Token tidak valid'];
            }
            // Validasi request
            $validated = $request->validate([
                'CCY' => 'nullable|string',
                'VANO' => 'required|string',
                'TRXDATE' => 'required|string',
                'METHOD' => 'required|string|in:INQUIRY',
                'USERNAME' => 'nullable|string',
                'PASSWORD' => 'nullable|string',
                'CHANNELID' => 'nullable|string',
                'REFNO' => 'nullable|string'
            ]);

            // Proses inquiry
            $result = $this->vaService->inquiry($validated);

            // Log hasil inquiry
            Logger::log('va_inquiry_controller', $validated, $result, null, time());

            // Kembalikan response
            return response()->json($result);
        } catch (\Exception $e) {
            // Log error
            Logger::log('va_inquiry_controller', $request->all(), null, $e->getMessage(), time());

            // Kembalikan response error
            return response()->json([
                'ERR' => '99',
                'METHOD' => 'INQUIRY',
                'CCY' => $request->input('CCY', '360'),
                'BILL' => '0',
                'DESCRIPTION' => 'SISTEM ERROR',
                'DESCRIPTION2' => '',
                'CUSTNAME' => ''
            ]);
        }
    }
}
