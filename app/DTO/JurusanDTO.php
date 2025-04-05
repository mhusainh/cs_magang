<?php

namespace App\DTO;

class JurusanDTO
{
    public static function createJurusanDTO(
        string $jurusan,
        ?string $jenjangSekolah
    ): array {
        return [
            'jurusan' => $jurusan,
            'jenjang_sekolah' => $jenjangSekolah,
        ];
    }

    public static function updateJurusanDTO(
        int $id,
        ?string $jurusan,
        ?string $jenjangSekolah
    ): array {
        return array_filter([
            'id' => $id,
            'jurusan' => $jurusan,
            'jenjang_sekolah' => $jenjangSekolah
        ]);
    }
}