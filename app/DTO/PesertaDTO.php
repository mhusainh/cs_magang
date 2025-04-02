<?php

namespace App\DTO;

class PesertaDTO
{
    public static function createPesertaDTO(
        int $user_id,
        string $nama,
        string $no_telp,
        string $jenis_kelamin,
        string $jenjang_sekolah,
    ): array {
        return [
            'user_id' => $user_id,
            'nama' => $nama,
            'no_telp' => $no_telp,
            'jenis_kelamin' => $jenis_kelamin,
            'jenjang_sekolah' => $jenjang_sekolah,
        ];
    }

    public static function updatePesertaDTO(
        int $id,
        int $user_id,
        string $nama,
        string $no_telp,
        string $jenis_kelamin,
        string $jenjang_sekolah,
        string $nisn,
        string $tempat_lahir,
        string $tanggal_lahir,
        string $alamat,
        ?int $jurusan1_id = null,
        ?int $jurusan2_id = null
    ): array {
        return [
            'id' => $id,
            'user_id' => $user_id,
            'nama' => $nama,
            'no_telp' => $no_telp,
            'jenis_kelamin' => $jenis_kelamin,
            'jenjang_sekolah' => $jenjang_sekolah,
            'nisn' => $nisn,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'jurusan1_id' => $jurusan1_id,
            'jurusan2_id' => $jurusan2_id
        ];
    }
    public static function inputFormPesertaDTO(
        string $nisn,
        string $tempat_lahir,
        string $tanggal_lahir,
        string $alamat,
        ?int $jurusan1_id = null,
        ?int $jurusan2_id = null
    ): array {
        return [
            'nisn' => $nisn,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'jurusan1_id' => $jurusan1_id,
            'jurusan2_id' => $jurusan2_id
        ];
    }
    public static function updatePesertaByUserDTO(
        string $nama,
        string $no_telp,
        string $jenis_kelamin,
        string $jenjang_sekolah,
        string $nisn,
        string $tempat_lahir,
        string $tanggal_lahir,
        string $alamat,
        ?int $jurusan1_id = null,
        ?int $jurusan2_id = null
    ): array {
        return [
            'nama' => $nama,
            'no_telp' => $no_telp,
            'jenis_kelamin' => $jenis_kelamin,
            'jenjang_sekolah' => $jenjang_sekolah,
            'nisn' => $nisn,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'alamat' => $alamat,
            'jurusan1_id' => $jurusan1_id,
            'jurusan2_id' => $jurusan2_id
        ];
    }
}
