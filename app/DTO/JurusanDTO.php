<?php

namespace App\DTO;

class JurusanDTO
{
    public static function createJurusanDTO(
        string $jurusan,
        string $jenjang_sekolah
    ): array {
        return [
            'jurusan' => $jurusan,
            'jenjang_sekolah' => $jenjang_sekolah,
        ];
    }
}