<?php

namespace App\DTO;

class UserDTO
{
    // Login DTO
    public static function UserLoginDTO(string $no_telp): array
    {
        return [
            'no_telp' => $no_telp,
        ];
    }

    // Register 
    public static function UserRegisterDTO(
        string $nama,
        string $jenis_kelamin,
        string $no_telp,
        string $jenjang_sekolah
    ): array {
        return [
            'nama' => $nama,
            'jenis_kelamin' => $jenis_kelamin,
            'no_telp' => $no_telp,
            'jenjang_sekolah' => $jenjang_sekolah
        ];
    }

    // Update 
    public static function UserUpdateDTO(
        int $id,
        string $no_telp,
    ): array {
        return [
            'id' => $id,
            'no_telp' => $no_telp,
        ];
    }
} 