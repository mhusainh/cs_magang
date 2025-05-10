<?php

namespace App\Services;

use App\Helpers\JWT;
use GuzzleHttp\Client;
use App\Helpers\Logger;
use GuzzleHttp\Exception;
use App\Repositories\TransaksiRepository;
use App\Repositories\TagihanRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;

class VaService
{
    public function __construct(
        private TransaksiRepository $transaksiRepository,
        private TagihanRepository $tagihanRepository,
        private UserRepository $userRepository
    ) {}

    public function inquiry(array $data): array
    {
        try {
            // Validasi VA number dan cari tagihan
            if (empty($data['VANO']) || !($tagihan = $this->tagihanRepository->getByVaNumber($data['VANO']))) {
                return [
                    'ERR' => '15',
                    'METHOD' => 'INQUIRY',
                    'CCY' => $data['CCY'] ?? '360',
                    'BILL' => '0',
                    'DESCRIPTION' => 'VA NUMBER TIDAK DITEMUKAN',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => ''
                ];
            }

            // Cek apakah tagihan sudah terlunasi
            if ($tagihan->status == 1) {
                return [
                    'ERR' => '88',
                    'METHOD' => 'INQUIRY',
                    'CCY' => $data['CCY'] ?? '360',
                    'BILL' => '0',
                    'DESCRIPTION' => 'TAGIHAN SUDAH TERLUNASI',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => ''
                ];
            }

            // Ambil data user dan peserta
            $user = $this->userRepository->findById($tagihan->user_id);
            $peserta = $user->peserta ?? null;

            // Persiapkan nama customer sesuai ketentuan
            $custName = '';
            if ($peserta) {
                // Ubah ke huruf kapital dan hapus simbol
                $custName = preg_replace('/[^A-Za-z0-9\s]/', '', strtoupper($peserta->nama));
                // Potong maksimal 30 karakter
                $custName = substr($custName, 0, 30);
            }

            // Persiapkan jumlah tagihan
            $billAmount = $tagihan->total;
            if ($billAmount < 0) {
                $billAmount = 0;
            }

            // Kalikan dengan 100 untuk format decimal
            $billAmount = (int)($billAmount * 100);

            // Persiapkan deskripsi
            $description = 'TAGIHAN ' . strtoupper($tagihan->nama_tagihan);
            $description = substr($description, 0, 40);

            // Detail tagihan
            $description2 = 'Detail: ' . $tagihan->nama_tagihan;
            if ($peserta) {
                $description2 .= ' - ' . $peserta->nama;
            }
            $description2 = substr($description2, 0, 256);

            // Kembalikan response sukses
            return [
                'ERR' => '00',
                'METHOD' => 'INQUIRY',
                'CCY' => $data['CCY'] ?? '360',
                'BILL' => (string)$billAmount,
                'DESCRIPTION' => $description,
                'DESCRIPTION2' => $description2,
                'CUSTNAME' => $custName
            ];
        } catch (\Exception $e) {
            $errorMessage = 'Error sistem pada inquiry VA: ' . $e->getMessage();
            Logger::log('va_inquiry_service', $data, null, $errorMessage, time());

            return [
                'ERR' => '12',
                'METHOD' => 'INQUIRY',
                'CCY' => $data['CCY'] ?? '360',
                'BILL' => '0',
                'DESCRIPTION' => 'SISTEM ERROR',
                'DESCRIPTION2' => '',
                'CUSTNAME' => ''
            ];
        }
    }

    public function payment(array $data): array
    {
        try {
            // Validasi VA number dan cari tagihan
            if (empty($data['VANO']) || !($tagihan = $this->tagihanRepository->getByVaNumber($data['VANO']))) {
                return [
                    'ERR' => '15',
                    'METHOD' => 'PAYMENT',
                    'CCY' => $data['CCY'] ?? '360',
                    'BILL' => '0',
                    'DESCRIPTION' => 'VA NUMBER TIDAK DITEMUKAN',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => ''
                ];
            }

            // Cek apakah tagihan sudah terlunasi
            if ($tagihan->status == 1) {
                return [
                    'ERR' => '88',
                    'METHOD' => 'PAYMENT',
                    'CCY' => $data['CCY'] ?? '360',
                    'BILL' => '0',
                    'DESCRIPTION' => 'TAGIHAN SUDAH TERLUNASI',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => ''
                ];
            }            

            // Ambil data user dan peserta
            $user = $this->userRepository->findById($tagihan->user_id);
            $peserta = $user->peserta ?? null;

            // Persiapkan nama customer sesuai ketentuan
            $custName = '';
            if ($peserta) {
                // Ubah ke huruf kapital dan hapus simbol
                $custName = preg_replace('/[^A-Za-z0-9\s]/', '', strtoupper($peserta->nama));
                // Potong maksimal 30 karakter
                $custName = substr($custName, 0, 30);
            }

            // Persiapkan jumlah tagihan
            $billAmount = $tagihan->total;
            if ($billAmount < 0) {
                $billAmount = 0;
            }

            // Kalikan dengan 100 untuk format decimal
            $billAmount = (int)($billAmount * 100);

            if ($data['PAYMENT'] != $billAmount) {
                return [
                    'ERR' => '16',
                    'METHOD' => 'PAYMENT',
                    'CCY' => $data['CCY']?? '360',
                    'BILL' => $billAmount,
                    'DESCRIPTION' => 'Jumlah pembayaran tidak sesuai',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => $custName
                ];
            }

            // Persiapkan deskripsi
            $description = 'TAGIHAN ' . strtoupper($tagihan->nama_tagihan);
            $description = substr($description, 0, 40);

            // Update status tagihan menjadi lunas
            $updateTagihan = $this->tagihanRepository->update($tagihan, [
                'status' => 1,
            ]);
            if (!$updateTagihan) {
                // Jika gagal update tagihan, kembalikan error
                Logger::log('va_payment_error', $data, null, 'Gagal update tagihan', time());
                return [
                    'ERR' => '12',
                    'METHOD' => 'PAYMENT',
                    'CCY' => $data['CCY'] ?? '360',
                    'BILL' => '0',
                    'DESCRIPTION' => 'SISTEM ERROR',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => ''
                ];
            }

            // Buat data transaksi dari data pembayaran
            $transaksiData = [
                'user_id' => $tagihan->user_id,
                'tagihan_id' => $tagihan->id,
                'status' => 1,
                'total' => $data['PAYMENT'],
                'created_time' => time(),
                'va_number' => $data['VANO'],
                'method' => $data['METHOD'],
                'ref_no' => $data['REFNO'],
            ];

            // Simpan transaksi ke database
            $transaksi = $this->transaksiRepository->create($transaksiData);
            if (!$transaksi) {
                // Jika gagal menyimpan transaksi, kembalikan error
                Logger::log('va_payment_error', $data, null, 'Gagal menyimpan transaksi', time());
                return [
                    'ERR' => '99',
                    'METHOD' => 'PAYMENT',
                    'CCY' => $data['CCY'] ?? '360',
                    'BILL' => '0',
                    'DESCRIPTION' => 'SISTEM ERROR',
                    'DESCRIPTION2' => '',
                    'CUSTNAME' => ''
                ];
            }

            // Kembalikan response sukses
            return [
                'ERR' => '00',
                'METHOD' => 'PAYMENT',
                'CCY' => $data['CCY'] ?? '360',
                'BILL' => (string)$billAmount,
                'DESCRIPTION' => $description,
                'DESCRIPTION2' => '',
                'CUSTNAME' => $custName
            ];
        } catch (\Exception $e) {
            $errorMessage = 'Error sistem pada payment VA: ' . $e->getMessage();
            Logger::log('va_payment_service', $data, null, $errorMessage, time());

            return [
                'ERR' => '99',
                'METHOD' => 'PAYMENT',
                'CCY' => $data['CCY'] ?? '360',
                'BILL' => '0',
                'DESCRIPTION' => 'SISTEM ERROR',
                'DESCRIPTION2' => '',
                'CUSTNAME' => ''
            ];
        }
    }
}
