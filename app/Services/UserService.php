<?php

namespace App\Services;

use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Repositories\PesertaRepository;
use App\Http\Resources\User\GetResource;
use App\Http\Resources\User\CardResource;
use App\Repositories\TransaksiRepository;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private PesertaRepository $pesertaRepository,
        private TransaksiRepository $transaksiRepository
    ) {}


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

            $result = $this->pesertaRepository->create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'no_telp' => $data['no_telp'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'jenjang_sekolah' => $data['jenjang_sekolah'],
            ]);
            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Gagal membuat peserta',
                    'code' => 402
                ];
            }

            return [
                'data'  => $user,
                'success' => true,
                'message' => 'Pendaftaran berhasil',
                'code' => 201
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

    public function getAll($filters = []): array
    {
        $users = $this->userRepository->getAll($filters);
        if (!$users) {
            return [
                'success' => false,
                'message' => 'User tidak tersedia'
            ];
        }

        // Set pagination data
        $pagination = [
            'page' => $users->currentPage(),
            'per_page' => $users->perPage(),
            'total_items' => $users->total(),
            'total_pages' => $users->lastPage()
        ];

        // Set current filters untuk response
        $currentFilters = [
            'search' => $filters['search'] ?? '',
            'start_date' => $filters['start_date'] ?? '',
            'end_date' => $filters['end_date'] ?? '',
            'status' => $filters['status'] ?? '',
            'sort_by' => $filters['sort_by'] ?? '',
            'sort_direction' => $filters['sort_direction'] ?? '',
        ];

        return [
            'success' => true,
            'data' => GetResource::collection($users),
            'message' => 'User berhasil diambil',
            'pagination' => $pagination,
            'current_filters' => $currentFilters
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

    public function progressPayment(int $userId): array
    {
        try {
            $user = $this->userRepository->findById($userId);
            if (!$user || !$user->peserta || !$user->peserta->jurusan1) {
                return [
                    'success' => false,
                    'message' => 'Data peserta tidak lengkap'
                ];
            }

            $isReguler = $user->peserta->jurusan1->jurusan === 'reguler';

            $unpaid = $isReguler ? $user->peserta->pengajuan_biaya : $user->peserta->wakaf;
            if (!is_numeric($unpaid) || $unpaid <= 0) {
                return [
                    'success' => false,
                    'message' => 'Jumlah tagihan tidak valid'
                ];
            }

            $paid = $isReguler
                ? $this->transaksiRepository->findPengajuanBiayaByUserId($userId)
                : $this->transaksiRepository->findWakafByUserId($userId);

            $paidSum = $paid ? $paid->sum('total') : 0;

            // Hindari pembagian dengan nol
            $progress = $unpaid > 0 ? min(($paidSum / $unpaid) * 100, 100) : 0;

            return [
                'success' => true,
                'data' => [
                    'progress' => round($progress, 2),
                    'paid' => $paidSum,
                    'unpaid' => $unpaid,
                ],
                'message' => 'Progress pembayaran berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil progress pembayaran'
            ];
        }
    }

    public function getDeleted($filters = []): array
    {
        $users = $this->userRepository->getTrash($filters);
        if (!$users) {
            return [
                'success' => false,
                'message' => 'User tidak tersedia'
            ];
        }

        // Set pagination data
        $pagination = [
            'page' => $users->currentPage(),
            'per_page' => $users->perPage(),
            'total_items' => $users->total(),
            'total_pages' => $users->lastPage()
        ];

        // Set current filters untuk response
        $currentFilters = [
            'search' => $filters['search'] ?? '',
            'start_date' => $filters['start_date'] ?? '',
            'end_date' => $filters['end_date'] ?? '',
            'status' => $filters['status'] ?? '',
            'sort_by' => $filters['sort_by'] ?? '',
            'sort_direction' => $filters['sort_direction'] ?? '',
        ];

        return [
            'success' => true,
            'data' => GetResource::collection($users),
            'message' => 'User berhasil diambil',
            'pagination' => $pagination,
            'current_filters' => $currentFilters
        ];
    }

    public function restore(int $id): array
    {
        $user = $this->userRepository->findById($id);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        $result = $this->userRepository->restore($user);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'User gagal dikembalikan'
            ];
        }

        return [
            'success' => true,
            'data' => $user,
            'message' => 'User berhasil dikembalikan'
        ];
    }
}
