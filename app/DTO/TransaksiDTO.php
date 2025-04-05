<?php

namespace App\DTO;

class TransaksiDTO
{
    /**
     * Membuat DTO untuk transaksi baru
     */
    public static function createTransaksiDTO(
        int $total,
        string $va_number,
        string $transaction_qr_id,
        string $method,
        string $ref_no
    ): array {
        return [
            'total' => $total,
            'va_number' => $va_number,
            'transaction_qr_id' => $transaction_qr_id,
            'method' => $method,
            'ref_no' => $ref_no
        ];
    }

    /**
     * Membuat DTO untuk update transaksi
     */
    public static function updateTransaksiDTO(
        int $id,
        ?int $user_id,
        int $tagihan_id,
        string $status,
        int $total,
        string $va_number,
        string $transaction_qr_id,
        string $method,
        string $ref_no
    ): array {
        return [
            'id' => $id,
            'user_id' => $user_id,
            'tagihan_id' => $tagihan_id,
            'status' => $status,
            'total' => $total,
            'va_number' => $va_number,
            'transaction_qr_id' => $transaction_qr_id,
            'method' => $method,
            'ref_no' => $ref_no
        ];
    }
}