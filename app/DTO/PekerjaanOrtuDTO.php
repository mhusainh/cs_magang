<?php

namespace App\DTO;

class PekerjaanOrtuDTO
{
    public static function createPekerjaanOrtuDTO(string $namaPekerjaan): array
    {
        return [
            'nama_pekerjaan' => $namaPekerjaan
        ];
    }

    public static function updatePekerjaanOrtuDTO(int $id, ?string $namaPekerjaan): array
    {
        return array_filter([
            'id' => $id,
            'nama_pekerjaan' => $namaPekerjaan
        ]);
    }
} 