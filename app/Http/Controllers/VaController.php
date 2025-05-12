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
        $token = $request->query('token');
        try {
            $decodedToken = JWT::decode($token, $_ENV['QRIS_JWT_SECRET'], ['HS256']);
        } catch (\Exception $e) {
            $payload = [
                'CCY' => '360',
                'ERR' => '30',
                'BILL' => '0',
                'DESCRIPTION' => 'TOKEN INVALID',
                'DESCRIPTION2' => '',
                'METHOD' => '',
                'CUSTNAME' => ''
            ];
            $token = JWT::encode($payload, $_ENV['QRIS_JWT_SECRET'], 'HS256');
            Logger::log('va', $payload, null, 'Token tidak valid: ' . $e->getMessage(), time());
            // Mengembalikan token tanpa tanda petik dalam format yang sama dengan respons asli
            return response($token)->header('Content-Type', 'application/json');
        }
        $data = (array) $decodedToken;
        $feature = '';

        if ($data['METHOD'] == 'INQUIRY') {
            $feature = 'va_inquiry';
            $response = $this->vaService->inquiry($data);
        } elseif ($data['METHOD'] == 'PAYMENT') {
            $feature = 'va_payment';
            $response = $this->vaService->payment($data);
        }

        Logger::log($feature, $data, $response, null, time());

        $token = JWT::encode($response, $_ENV['QRIS_JWT_SECRET'], 'HS256');

        // Kembalikan response tanpa tanda petik
        return response($token)->header('Content-Type', 'application/json');
    }
}
