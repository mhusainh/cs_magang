<?php

namespace App\DTO;

class TagihanDTO
{
    /**
     * Membuat DTO untuk pembuatan tagihan baru
     */
    public static function createTagihanDTO(
        int $user_id,
        string $nama_tagihan,
        int $total,
    ): array {
        return [
            'user_id' => $user_id,
            'nama_tagihan' => $nama_tagihan,
            'total' => $total,
        ];
    }

    /**
     * Membuat DTO untuk update tagihan
     */
    public static function updateTagihanDTO(
        int $id,
        string $nama_tagihan,
        int $total,
        string $status,
        string $va_number,
        string $transaction_qr_id,
        string $created_time
    ): array {
        return [
            'id' => $id,
            'nama_tagihan' => $nama_tagihan,
            'total' => $total,
            'status' => $status,
            'va_number' => $va_number,
            'transaction_qr_id' => $transaction_qr_id,
            'created_time' => $created_time
        ];
    }
}