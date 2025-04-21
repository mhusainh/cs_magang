<?php

namespace App\DTO;

class BiodataOrtuDTO
{
    public static function createBiodataOrtuDTO(
        int $peserta_id,
        string $nama_ayah,
        string $nama_ibu,
        string $no_telp,
        int $pekerjaan_ayah_id,
        int $pekerjaan_ibu_id,
        int $penghasilan_ortu_id
    ): array {
        return [
            'peserta_id' => $peserta_id,
            'nama_ayah' => $nama_ayah,
            'nama_ibu' => $nama_ibu,
            'no_telp' => $no_telp,
            'pekerjaan_ayah_id' => $pekerjaan_ayah_id,
            'pekerjaan_ibu_id' => $pekerjaan_ibu_id,
            'penghasilan_ortu_id' => $penghasilan_ortu_id
        ];
    }

    public static function updateBiodataOrtuDTO(
        int $id,
        ?string $nama_ayah,
        ?string $nama_ibu,
        ?string $no_telp,
        ?int $pekerjaan_ayah_id,
        ?int $pekerjaan_ibu_id,
        ?int $penghasilan_ortu_id
    ): array {
        return array_filter([
            'id' => $id,
            'nama_ayah' => $nama_ayah,
            'nama_ibu' => $nama_ibu,
            'no_telp' => $no_telp,
            'pekerjaan_ayah_id' => $pekerjaan_ayah_id,
            'pekerjaan_ibu_id' => $pekerjaan_ibu_id,
            'penghasilan_ortu_id' => $penghasilan_ortu_id
        ]);
    }
}