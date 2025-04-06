<?php

namespace App\DTO;

class ProgressUserDTO
{
    public static function createProgressUserDTO(
        string $user_id,
        string $progress
    ): array {
        return [
            'user_id' => $user_id,
            'progress' => $progress,
        ];
    }

    public static function updateProgressUserDTO(
        int $id,
        string $user_id,
        string $progress
    ): array {
        return [
            'id' => $id,
            'user_id' => $user_id,
            'progress' => $progress,
        ];
    }
}