<?php

namespace App\Services;

use App\Http\Resources\PengajuanBiaya\GetResource;
use App\Repositories\PengajuanBiayaRepository;

class PengajuanBiayaService
{
    public function __construct(
        private PengajuanBiayaRepository $pengajuanBiayaRepository
    ) {}

    public function getAll(): array
    {
        $result = $this->pengajuanBiayaRepository->getAll();
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Pengajuan Biaya berhasil ditemukan',
            'data' => GetResource::collection($result),
        ];
    }

    public function getById($id): array
    {
        $result = $this->pengajuanBiayaRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Pengajuan Biaya berhasil ditemukan',
            'data' => new GetResource($result),
        ];
    }

    public function create($data): array
    {
        $result = $this->pengajuanBiayaRepository->create($data);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal ditambahkan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditambahkan',
            'data' => new GetResource($result),
        ];
    }

    public function update($id, $data): array
    {
        $pengajuanBiaya = $this->pengajuanBiayaRepository->findById($id);
        if (!$pengajuanBiaya) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->pengajuanBiayaRepository->update($pengajuanBiaya, $data);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal diupdate',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Pengajuan Biaya berhasil diupdate',
            'data' => new GetResource($result),
        ];
    }

    public function delete($id): array
    {
        $result = $this->pengajuanBiayaRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->pengajuanBiayaRepository->delete($result);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal dihapus',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Pengajuan Biaya berhasil dihapus',
        ];
    }
}
