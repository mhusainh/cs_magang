<?php

namespace App\Services;


use App\Repositories\PesertaRepository;
use App\Http\Resources\Peserta\GetDetailResource;
use App\Repositories\JurusanRepository;

class PesertaService
{
    public function __construct(
        private PesertaRepository $pesertaRepository,
        private JurusanRepository $jurusanRepository
    ) {}

    public function create(array $data): array
    {
        try {
            $this->pesertaRepository->create($data);

            return [
                'success' => true,
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
        try {
            $peserta = $this->pesertaRepository->findById($id);
            if (!$peserta) {
                return [
                    'success' => false,
                    'message' => 'Peserta tidak ditemukan'
                ];
            }

            // Get jurusan data and handle potential null cases
            $jurusan1 = $peserta->jurusan1_id ? $this->jurusanRepository->findById($peserta->jurusan1_id) : null;
            $jurusan2 = $peserta->jurusan2_id ? $this->jurusanRepository->findById($peserta->jurusan2_id) : null;

            $peserta->jurusan1_id = $jurusan1 ? $jurusan1->jurusan : null;
            $peserta->jurusan2_id = $jurusan2 ? $jurusan2->jurusan : null;

            return [
                'success' => true,
                'data' => new GetDetailResource($peserta),
                'message' => 'Peserta berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta'
            ];
        }
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
            'data' => new GetDetailResource($peserta),
            'message' => 'Peserta berhasil diambil'
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
                // 'data' => RegisterResource::collection($peserta),
                'message' => 'Data peserta berhasil diambil'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peserta'
            ];
        }
    }
}
