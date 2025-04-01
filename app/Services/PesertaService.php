<?php

namespace App\Services;

use App\DTO\PesertaDTO;
use App\Models\PesertaPpdb;
use App\Repositories\PesertaRepository;
use App\Http\Resources\RegisterResource;

class PesertaService
{
    public function __construct(private PesertaRepository $pesertaRepository)
    {
    }

    public function create(array $data): array
    {
        try {
            $peserta = $this->pesertaRepository->create($data);

            return [
                'success' => true,
                'data' => new RegisterResource($peserta),
                'message' => 'Peserta berhasil ditambahkan'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan peserta'
            ];
        }
    }

    public function getById(int $id): array
    {
        $peserta = $this->pesertaRepository->findById($id);
        
        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => new RegisterResource($peserta)
        ];
    }

    public function getByUserId(int $userId): array
    {
        $peserta = $this->pesertaRepository->findByUserId($userId);
        
        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => new RegisterResource($peserta)
        ];
    }

    public function update(int $id, array $data): array
    {
        $peserta = $this->pesertaRepository->findById($id);
        
        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        try {
            $this->pesertaRepository->update($peserta, $data);

            return [
                'success' => true,
                'data' => new RegisterResource($peserta->fresh()),
                'message' => 'Peserta berhasil diperbarui'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui peserta'
            ];
        }
    }

    public function delete(int $id): array
    {
        $peserta = $this->pesertaRepository->findById($id);
        
        if (!$peserta) {
            return [
                'success' => false,
                'message' => 'Peserta tidak ditemukan'
            ];
        }

        try {
            $this->pesertaRepository->delete($peserta);

            return [
                'success' => true,
                'message' => 'Peserta berhasil dihapus'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus peserta'
            ];
        }
    }

    public function getAll(): array
    {
        try {
            $peserta = $this->pesertaRepository->getAll();

            return [
                'success' => true,
                'data' => RegisterResource::collection($peserta)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta'
            ];
        }
    }
} 