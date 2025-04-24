<?php

namespace App\Services;

use App\Helpers\JWT;
use GuzzleHttp\Client;
use App\Helpers\Logger;
use GuzzleHttp\Exception;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class QrisService
{
    public function generateQris(array $data): array
    {
        $payload = [];
        try {
            $payload = [
                'accountNo' => $_ENV['QRIS_ACCOUNT_NO'],
                'amount' => (string) $data['total'],
                'mitraCustomerId' => $_ENV['QRIS_MITRA_CUSTOMER_ID'],
                'transactionId' => $data['created_time'],
                'tipeTransaksi' => $_ENV['QRIS_TIPE_TRANSAKSI'],
                'vano' => $data['va_number']
            ];

            $token = JWT::encode($payload, $_ENV['QRIS_JWT_SECRET'], 'HS256');

            try {
                $client = new Client(['timeout' => 30]);
                $response = $client->post($_ENV['QRIS_URL'], [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ],
                    'query' => ['token' => (string) $token]
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if ($result['responseCode'] === '00') {
                    Logger::log('generate_qris', $payload, $result, null, $data['created_time']);

                    return [
                        'success' => true,
                        'data' => $result['transactionDetail'],
                        'message' => 'QRIS berhasil dibuat'
                    ];
                }

                $errorMessage = 'Gagal membuat QRIS: ' . ($result['responseMessage'] ?? 'Response tidak valid');
                Logger::log('generate_qris', $payload, null, $errorMessage, $data['created_time']);

                return [
                    'success' => false,
                    'message' => $errorMessage
                ];
            } catch (ConnectException $e) {
                $errorMessage = 'Gagal terhubung ke server QRIS: ' . $e->getMessage();
                Logger::log('generate_qris', $payload, null, $errorMessage, $data['created_time']);
                return ['success' => false, 'message' => $errorMessage];
            } catch (RequestException $e) {
                $errorMessage = 'Error pada request QRIS: ' . $e->getMessage();
                Logger::log('generate_qris', $payload, null, $errorMessage, $data['created_time']);
                return ['success' => false, 'message' => $errorMessage];
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error sistem pada generate QRIS: ' . $e->getMessage();
            if (!empty($payload)) {
                Logger::log('generate_qris', $payload, null, $errorMessage, $data['created_time']);
            }
            return ['success' => false, 'message' => $errorMessage];
        }
    }

    public function checkStatus($data): array
    {
        $payload = [];
        try {
            // Convert Tagihan model to array if it's an object
            if (is_object($data)) {
                $data = $data->toArray();
            }

            // Validate required fields
            if (!isset($data['total']) || !isset($data['created_time']) || !isset($data['transaction_qr_id'])) {
                return [
                    'success' => false,
                    'message' => 'Data tidak lengkap. Diperlukan: total, created_time, dan transaction_qr_id'
                ];
            }

            $payload = [
                'accountNo' => $_ENV['QRIS_ACCOUNT_NO'],
                'amount' => (string) $data['total'],
                'merchantId' => $_ENV['QRIS_MERCHANT_ID'],
                'mitraCustomerId' => $_ENV['QRIS_MITRA_CUSTOMER_ID'],
                'transactionId' => $data['created_time'],
                'transactionQrId' => $data['transaction_qr_id'],
                'tipeTransaksi' => $_ENV['QRIS_TIPE_TRANSAKSI'],
            ];

            $token = JWT::encode($payload, $_ENV['QRIS_JWT_SECRET'], 'HS256');

            try {
                $client = new Client(['timeout' => 30]);
                $response = $client->post($_ENV['QRIS_URL'], [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $token,
                        'Content-Type' => 'application/json'
                    ],
                    'json' => $payload
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (isset($result['responseCode']) && $result['responseCode'] === '00') {
                    Logger::log('check_status', $payload, $result, null, $data['created_time']);

                    return [
                        'success' => true,
                        'data' => $result['transactionDetail'],
                        'message' => 'Status berhasil diperoleh'
                    ];
                }

                $errorMessage = 'Gagal memperoleh status: ' . ($result['responseMessage'] ?? 'Response tidak valid');
                Logger::log('check_status', $payload, null, $errorMessage, $data['created_time']);
                return [
                    'success' => false,
                    'message' => $errorMessage
                ];
            } catch (ConnectException $e) {
                $errorMessage = 'Gagal terhubung ke server QRIS: ' . $e->getMessage();
                Logger::log('check_status', $payload, null, $errorMessage, $data['created_time']);
                return ['success' => false, 'message' => $errorMessage];
            } catch (RequestException $e) {
                $errorMessage = 'Error pada request QRIS: ' . $e->getMessage();
                Logger::log('check_status', $payload, null, $errorMessage, $data['created_time']);
                return ['success' => false, 'message' => $errorMessage];
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error sistem pada check status QRIS: ' . $e->getMessage();
            if (!empty($payload)) {
                Logger::log('check_status', $payload, null, $errorMessage, $data['created_time'] ?? null);
            }
            return ['success' => false, 'message' => $errorMessage];
        }
    }
}
