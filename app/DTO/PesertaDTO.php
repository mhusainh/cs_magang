<?php

namespace App\DTO;

class PesertaDTO
{
    // public static function createPesertaDTO(
    //     int $user_id,
    //     string $nama,
    //     string $no_telp,
    //     string $jenis_kelamin,
    //     string $jenjang_sekolah,
    // ): array {
    //     return [
    //         'user_id' => $user_id,
    //         'nama' => $nama,
    //         'no_telp' => $no_telp,
    //         'jenis_kelamin' => $jenis_kelamin,
    //         'jenjang_sekolah' => $jenjang_sekolah,
    //     ];
    // }

    public static function updatePesertaDTO(
        int $id,
        int $user_id,
        string $nama,
        string $jenis_kelamin,
        string $jenjang_sekolah,
        string $nisn,
        string $tempat_lahir,
        string $tanggal_lahir,
        string $alamat,
        string $asal_sekolah,
        ?int $jurusan1_id = null,
    ): array {
        return [
            'id' => $id,
            'user_id' => $user_id,
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'jenjang_sekolah' => $jenjang_sekolah,
            'nisn' => $nisn,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'asal_sekolah' => $asal_sekolah,
            'jurusan1_id' => $jurusan1_id,
        ];
    }
    public static function inputFormPesertaDTO(
        string $nisn,
        string $tempat_lahir,
        string $tanggal_lahir,
        string $alamat,
        string $asal_sekolah,
        ?int $jurusan1_id = null,
    ): array {
        return [
            'nisn' => $nisn,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'asal_sekolah' => $asal_sekolah,
            'jurusan1_id' => $jurusan1_id,
        ];
    }
    public static function updatePesertaByUserDTO(
        string $nama,
        string $jenis_kelamin,
        string $jenjang_sekolah,
        string $nisn,
        string $tempat_lahir,
        string $tanggal_lahir,
        string $alamat,
        string $asal_sekolah,
        ?int $jurusan1_id = null,
    ): array {
        return [
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'jenjang_sekolah' => $jenjang_sekolah,
            'nisn' => $nisn,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'asal_sekolah' => $asal_sekolah,
            'jurusan1_id' => $jurusan1_id,
        ];
    }
}
