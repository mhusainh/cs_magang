<?php

namespace App\DTO;

class PesertaDTO
{
    public static function createPesertaDTO(
        int $user_id,
        string $nama,
        string $no_telp,
        string $jenis_kelamin,
        string $jenjang_sekolah
    ): array {
        return [
            'user_id' => $user_id,
            'nama' => $nama,
            'no_telp' => $no_telp,
            'jenis_kelamin' => $jenis_kelamin,
            'jenjang_sekolah' => $jenjang_sekolah
        ];
    }
} 