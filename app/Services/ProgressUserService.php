<?php

namespace App\Services;

use App\Repositories\ProgressUserRepository;

class ProgressUserService
{
    public function __construct(
        private ProgressUserRepository $progressUserRepository
    ) {}

    public function getAll(): array
    {
        try {
            $progressUser = $this->progressUserRepository->getAll();
            return [
                'success' => true,
                'data' => $progressUser,
                'message' => 'Daftar progress User berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar progress User: ' . $e->getMessage()
            ];
        }
    }

    public function getByUserId(int $id): array
    {
        try {
            $progressUser = $this->progressUserRepository->findByUserId($id);
            return [
                'success' => true,
                'data' => $progressUser,
                'message' => 'Daftar progress User berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil daftar progress User: ' . $e->getMessage()
            ];
        }
    }

    public function getById(int $id): array
    {
        try {
            $progressUser = $this->progressUserRepository->findById($id);

            if (!$progressUser) {
                return [
                    'success' => false,
                    'message' => 'Progress User tidak ditemukan'
                ];
            }

            return [
                'success' => true,
                'data' => $progressUser,
                'message' => 'Detail Progress User berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal mengambil detail progressUser: ' . $e->getMessage()
            ];
        }
    }

    public function create(array $data): array
    {
        try {
            $progressUser = $this->progressUserRepository->create($data);
            return [
                'success' => true,
                'data' => $progressUser,
                'message' => 'Progress User berhasil dibuat'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal membuat progressUser: ' . $e->getMessage()
            ];
        }
    }

    public function update(array $data, int $id): array
    {
        try {
            $progressUser = $this->progressUserRepository->findById($id);

            if (!$progressUser) {
                return [
                    'success' => false,
                    'message' => 'Progress User tidak ditemukan'
                ];
            }

            $updated = $this->progressUserRepository->update($progressUser, $data);

            return [
                'success' => true,
                'data' => $updated,
                'message' => 'progressUser berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal memperbarui progressUser: ' . $e->getMessage()
            ];
        }
    }

    public function updateProgress($progressUser, array $data): array
    {
        try {
            $updated = $this->progressUserRepository->update($progressUser, $data);

            return [
               'success' => true,
                'data' => $updated,
               'message' => 'Progress User berhasil diperbarui'
            ];
        }catch (\Exception $e) {
            return [
              'success' => false,
              'message' => 'Gagal memperbarui progressUser: '. $e->getMessage()
            ];
        }
    }
    public function delete(int $id): array
    {
        try {
            $progressUser = $this->progressUserRepository->findById($id);

            if (!$progressUser) {
                return [
                    'success' => false,
                    'message' => 'Progress User tidak ditemukan'
                ];
            }

            $this->progressUserRepository->delete($progressUser);

            return [
                'success' => true,
                'message' => 'progressUser berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Gagal menghapus progressUser: ' . $e->getMessage()
            ];
        }
    }
}
