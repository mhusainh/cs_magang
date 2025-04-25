<?php

namespace App\Services;

use App\Helpers\JWT;
use GuzzleHttp\Client;
use App\Helpers\Logger;
use GuzzleHttp\Exception;
use App\Repositories\TransaksiRepository;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class QrisService
{
    public function __construct(
        private TransaksiRepository $transaksiRepository
    ) {}
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

    public function checkStatus(array $data): array
    {
        $payload = [
            'accountNo' => $_ENV['QRIS_ACCOUNT_NO'],
            'amount' => (string) $data['total'],
            'merchantId' => $_ENV['QRIS_MERCHANT_ID'],
            'mitraCustomerId' => $_ENV['QRIS_MITRA_CUSTOMER_ID'],
            'transactionId' => $data['created_time'],
            'transactionQrId' => $data['transaction_qr_id'],
            'tipeTransaksi' => $_ENV['QRIS_TIPE_TRANSAKSI'],
        ];
        try {
            $token = JWT::encode($payload, $_ENV['QRIS_JWT_SECRET'], 'HS256');

            $client = new Client(['timeout' => 30]);
            $response = $client->post($_ENV['QRIS_URL'], [
                'query' => ['token' => (string) $token]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['responseCode'] === '00') {
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
            $errorMessage = 'Gagal terhubung ke server: ' . $e->getMessage();
            return ['success' => false, 'message' => $errorMessage];
        } catch (RequestException $e) {
            $errorMessage = 'Error pada request: ' . $e->getMessage();
            Logger::log('check_status', $payload, null, $errorMessage, $data['created_time']);
            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            $errorMessage = 'Error sistem: ' . $e->getMessage();
            if ($payload) {
                Logger::log('check_status', $payload, null, $errorMessage, $data['created_time']);
            }
            return ['success' => false, 'message' => $errorMessage];
        }
    }

    public function processWebhook(string $token, array $requestData): array
    {
        try {
            // Verifikasi token JWT
            try {
                $decodedToken = JWT::decode($token, $_ENV['QRIS_JWT_SECRET'], ['HS256']);
            } catch (\Exception $e) {
                Logger::log('webhook_qris', ['token' => $token], null, 'Token tidak valid: ' . $e->getMessage(), time());
                return ['success' => false, 'message' => 'Token tidak valid'];
            }

            // Log data webhook yang diterima
            Logger::log('webhook_qris', ['token' => $token, 'request' => $requestData], null, null, time());

            // Pastikan response code adalah 00 (sukses)
            if ($requestData['responseCode'] !== '00') {
                return ['success' => false, 'message' => 'Response code tidak valid'];
            }

            // Ambil data transaksi dari payload
            $transactionData = $requestData['data'] ?? null;
            if (!$transactionData) {
                return ['success' => false, 'message' => 'Data transaksi tidak ditemukan'];
            }

            // Cari transaksi berdasarkan transaction_qr_id
            $transaksi = $this->transaksiRepository->findByQrId($transactionData['transactionQrId']);
            if (!$transaksi) {
                return ['success' => false, 'message' => 'Transaksi tidak ditemukan'];
            }

            // Update status transaksi menjadi sukses
            $updateData = [
                'id' => $transaksi->id,
                'status' => 'success',
                'payment_date' => now()->format('Y-m-d H:i:s'),
                'description' => $transactionData['description'] ?? 'Pembayaran QRIS berhasil'
            ];

            $this->transaksiRepository->update($transaksi, $updateData);

            return [
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            Logger::log('webhook_qris', ['token' => $token, 'request' => $requestData], null, 'Error: ' . $e->getMessage(), time());
            return ['success' => false, 'message' => 'Error sistem: ' . $e->getMessage()];
        }
    }
}
