<?php

namespace App\Services;

use App\Helpers\JWT;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use App\Repositories\TagihanRepository;

class TagihanService
{
    public function __construct(
        private TagihanRepository $tagihanRepository
    ) {}

    public function getById(int $id, int $userId): array
    {
        try {
            $tagihan = $this->tagihanRepository->findUserById($id, $userId);

            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $tagihan,
                'message' => 'Detail tagihan berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $data = [
                'user_id' => $data['user_id'],
                'nama_tagihan' => $data['nama_tagihan'],
                'total' => $data['total'],
                'created_time' => now()->format('s') . substr(now()->format('u'), 0, 6),
                'va_number' => $_ENV['QRIS_VA_NUMBER'] . str_pad(random_int(1000000000, 9999999999), 10, '0', STR_PAD_LEFT),
            ];
            
            $tagihan = $this->tagihanRepository->create($data);
            if (!$tagihan) {
                return [
                   'success' => false,
                   'message' => 'Gagal membuat tagihan'
                ];
            }

            // Generate QRIS terlebih dahulu
            $qrisResult = $this->generateQris($data);
            if (!$qrisResult['success']) {
                return $qrisResult;
            }

            // Tambahkan data QRIS ke data transaksi
            $data['transaction_qr_id'] = $qrisResult['data']['transactionQrId'];
            $data['qr_data'] = $qrisResult['data']['rawQrData'];

            $tagihan = $this->tagihanRepository->update($tagihan, $data['transaction_qr_id']);
            if (!$tagihan) {
                return [
                  'success' => false,
                  'message' => 'Gagal memperbarui tagihan'
                ];
            }

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Tagihan berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($data['id']);

            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            // Validasi VA number unik kecuali untuk record yang sama
            if ($this->tagihanRepository->vaNumberExists($data['va_number'], $data['id'])) {
                return [
                    'success' => false,
                    'message' => 'VA Number sudah digunakan'
                ];
            }

            $updated = $this->tagihanRepository->update($tagihan, $data);
            if (!$updated) {
                return [
                 'success' => false,
                 'message' => 'Gagal memperbarui tagihan'
                ];
            }

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'Tagihan berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function delete(int $id): array
    {
        try {
            $tagihan = $this->tagihanRepository->findById($id);

            if (!$tagihan) {
                return [
                    'success' => false,
                    'message' => 'Tagihan tidak ditemukan'
                ];
            }

            $this->tagihanRepository->delete($tagihan);

            return [
                'success' => true,
                'message' => 'Tagihan berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus tagihan: ' . $e->getMessage()
            ];
        }
    }

    public function getAll(int $userId): array
    {
        try {
            $tagihans = $this->tagihanRepository->getAll($userId);
            return [
                'success' => true,
                'data' => $tagihans,
                'message' => 'Tagihan user berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil tagihan: ' . $e->getMessage()
            ];
        }
    }

    private function generateQris(array $data): array
    {
        try {
            $payload = [
                'accountNo' => '3010206072',
                'amount' => (string) $data['total'],
                'mitraCustomerId' => $_ENV['QRIS_MITRA_CUSTOMER_ID'],
                'transactionId' => $data['created_time'],
                'tipeTransaksi' => $_ENV['QRIS_TIPE_TRANSAKSI'],
                'vano' => $data['va_number']
            ];

            $token = JWT::encode($payload, 'TokenJWT_BMI_ICT', 'HS256');

            $client = new Client();
            $response = $client->post('http://103.23.103.43/apidevelopment/qris_dev/server_dev.php', [
                'query' => ['token' => (string) $token]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if ($result['responseCode'] === '00') {
                return [
                    'success' => true,
                    'data' => $result['transactionDetail'],
                    'message' => 'QRIS berhasil dibuat'
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal membuat QRIS: ' . $result['responseMessage']
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat QRIS: ' . $e->getMessage()
            ];
        }
    }
}
