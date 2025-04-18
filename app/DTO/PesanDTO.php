<?php

namespace App\DTO;

class PesanDTO
{
    public static function createPesanDTO(
        int $userId,
        string $judul,
        string $deskripsi,
    ): array {
        return [
            'user_id' => $userId,
            'judul' => $judul,
            'deskripsi' => $deskripsi,
        ];
    }

    public static function updatePesanDTO(
        string $judul,
        string $deskripsi,
    ): array {
        return [
            'judul' => $judul,
            'deskripsi' => $deskripsi,
        ];
    }
}