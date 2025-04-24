<?php

namespace App\Services;

use App\Http\Resources\BiayaPendaftaran\GetResource;
use App\Http\Resources\Jurusan\GetDetailResource;
use App\Repositories\BiayaPendaftaranRepository;

class BiayaPendaftaranService
{
    public function __construct(
        private BiayaPendaftaranRepository $biayaPendaftaranRepository
    ) {}

    public function getAll(): array
    {
        $result = $this->biayaPendaftaranRepository->getAll();
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => GetResource::collection($result),
        ];
    }

    public function getById($id): array
    {
        $result = $this->biayaPendaftaranRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => new GetResource($result),
        ];
    }

    public function create($data): array
    {
        $result = $this->biayaPendaftaranRepository->create($data);
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
            'data' => $result,
        ];
    }

    public function update($id, $data): array
    {
        $result = $this->biayaPendaftaranRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->biayaPendaftaranRepository->update($result, $data);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal diubah',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil diubah',
            'data' => $result,
        ];
    }

    public function delete($id): array
    {
        $result = $this->biayaPendaftaranRepository->findById($id);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        $result = $this->biayaPendaftaranRepository->delete($result);
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data gagal dihapus',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil dihapus',
            'data' => null,
        ];
    }

    public function getOnTop(): array
    {
        $result = $this->biayaPendaftaranRepository->getOnTop();
        if (!$result) {
            return [
                'success' => false,
                'message' => 'Data tidak ditemukan',
                'data' => null,
            ];
        }
        return [
            'success' => true,
            'message' => 'Data berhasil ditemukan',
            'data' => new GetResource($result),
        ];
    }
}
