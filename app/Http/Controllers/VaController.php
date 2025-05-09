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
    public function paymentVA(Request $request): JsonResponse
    {
        try {
            $token = $request->query('token');
            if (empty($token)) {
                $payload = [
                    'CCY' => '360',
                    'ERR' => '30',
                    'BILL' => '0',
                    'DESCRIPTION' => 'TOKEN INVALID',
                    'DESCRIPTION2' => '',
                    'METHOD' => '',
                    'CUSTNAME' => ''
                ];
                Logger::log('payment_va', $payload, null, 'Token tidak  valid', time());

                $token = JWT::encode($payload, $_ENV['QRIS_JWT_SECRET'], 'HS256');
                return response()->json([$token]);
            }
            try {
                $decodedToken = JWT::decode($token, $_ENV['QRIS_JWT_SECRET'], ['HS256']);
            } catch (\Exception $e) {
                Logger::log('payment_va', ['token' => $token], null, 'Token tidak valid: ' . $e->getMessage(), time());
            }

            if ($decodedToken['METHOD'] == 'INQUIRY') {
                $payload = [
                    
                ];
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
