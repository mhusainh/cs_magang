<?php

namespace App\DTO;

class KetentuanBerkasDTO
{
    public static function createKetentuanBerkasDTO(
        string $nama,
        string $jenjang_sekolah,
        int $is_required
    ): array {
        return [
            'nama' => $nama,
            'jenjang_sekolah' => $jenjang_sekolah,
            'is_required' => $is_required
        ];
    }
}