<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use App\Repositories\PesertaRepository;
use App\Http\Resources\User\CardResource;

class UserService
{
    public function __construct(private UserRepository $userRepository, private PesertaRepository $pesertaRepository) {}


    public function login(array $data): array
    {
        $user = $this->userRepository->findByPhone($data['no_telp']);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Pengguna tidak ditemukan'
            ];
        }

        if ($user->status == 0) {
            return [
                'success' => false,
                'message' => 'Pengguna belum membayar uang pendaftaran'
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
        try {
            $user = $this->userRepository->create([
                'no_telp' => $data['no_telp']
            ]);

            $this->pesertaRepository->create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'no_telp' => $data['no_telp'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'jenjang_sekolah' => $data['jenjang_sekolah'],
            ]);

            return [
                'data'  => $user,
                'success' => true,
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
        try {
            $user = $this->userRepository->findById($data['id']);
            if (!$user) {
                return [
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ];
            }

            $updated = $this->userRepository->update($user, [
                'no_telp' => $data['no_telp'],
            ]);

            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Gagal memperbarui user'
                ];
            }

            return [
                'success' => true,
                'message' => 'User berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            Log::error('Error updating user: ' . $e->getMessage(), [
                'user_id' => $data['id'],
                'data' => $data,
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui user: ' . $e->getMessage()
            ];
        }
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
            'data' => $users,
            'message' => 'User berhasil diambil'
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
            'data' => $user,
            'message' => 'User berhasil diambil'
        ];
    }
    public function cardUser(int $Id): array
    {
        $user = $this->userRepository->findByIdCard($Id);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => new CardResource($user),
            'message' => 'User berhasil diambil'
        ];
    }
}
