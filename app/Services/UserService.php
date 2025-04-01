<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\DTO\RegisterDTO;
use App\Models\User;
use App\Models\PesertaPpdb;
use App\Repositories\UserRepository;
use App\Http\Resources\RegisterResource;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Repositories\PesertaRepository;

class UserService
{
    public function __construct(private UserRepository $userRepository, private PesertaRepository $pesertaRepository)
    {
    }


    public function login(array $data): array
    {
        $user = $this->userRepository->findByPhone($data['no_telp']);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ];
        }

        $token = JWTAuth::fromUser($user);

        return [
            'success' => true,
            'data' => [
                'token' => $token,
                'type' => 'bearer',
            ],
            'message' => 'Login berhasil'
        ];
    }

    public function register(array $data): array
    {
        $existingUser = $this->userRepository->findByPhone($data['no_telp']);
        if ($existingUser) {
            return [
                'success' => false,
                'message' => 'Nomor telepon sudah digunakan'
            ];
        }

        try {
            $user = $this->userRepository->create([
                'no_telp' => $data['no_telp']
            ]);

            $peserta = $this->pesertaRepository->create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'no_telp' => $data['no_telp'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'jenjang_sekolah' => $data['jenjang_sekolah'],
            ]);

            return [
                'success' => true,
                'data' => new RegisterResource($peserta),
                'message' => 'Pendaftaran berhasil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat pendaftaran'
            ];
        }
    }

    public function update(array $data): array
    {
        $user = $this->userRepository->findById($data['id']);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        $existingUser = $this->userRepository->findByPhone($data['no_telp']);
        if ($existingUser && $existingUser->id !== $user->id) {
            return [
                'success' => false,
                'message' => 'Nomor telepon sudah digunakan'
            ];
        }

        $this->userRepository->update($user, [
            'no_telp' => $data['no_telp'],
        ]);

        return [
            'success' => true,
            'data' => $user->fresh(),
            'message' => 'User berhasil diperbarui'
        ];
    }

    public function delete(int $id): array
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        $this->userRepository->delete($user);
        
        return [
            'success' => true,
            'message' => 'User berhasil dihapus'
        ];
    }

    public function getAll(): array
    {
        $users = $this->userRepository->getAll();
        
        return [
            'success' => true,
            'data' => $users
        ];
    }

    public function getById(int $id): array
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => $user
        ];
    }

} 