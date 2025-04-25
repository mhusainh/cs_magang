<?php

namespace App\Repositories;

use App\Models\PesertaPpdb as Peserta;
use Illuminate\Database\Eloquent\Collection;

class PesertaRepository
{
    public function __construct(private Peserta $model) {}

    public function create(array $data): Peserta
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?Peserta
    {
        return $this->model->with([
            'jurusan1',
            'biodataOrtu.pekerjaanAyah',
            'biodataOrtu.pekerjaanIbu',
            'biodataOrtu.penghasilanOrtu'
        ])->where('id', $id)->first();
    }

    public function findByUserId(int $userId): ?Peserta
    {
        return $this->model->with([
            'jurusan1',
            'biodataOrtu.pekerjaanAyah',
            'biodataOrtu.pekerjaanIbu',
            'biodataOrtu.penghasilanOrtu',
            'berkas'
        ])->where('user_id', $userId)->first();
    }

    public function update(Peserta $peserta, array $data): bool
    {
        return $peserta->update($data);
    }

    public function delete(Peserta $peserta): bool
    {
        return $peserta->delete();
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nisn', 'like', "%{$search}%")
                    ->orWhere('no_telp', 'like', "%{$search}%")
                    ->orWhereHas('jurusan1', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('jurusan2', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('biodataOrtu', function ($q) use ($search) {
                        $q->where('nama_ayah', 'like', "%{$search}%")
                            ->orWhere('nama_ibu', 'like', "%{$search}%")
                            ->orWhereHas('pekerjaanAyah', function ($q) use ($search) {
                                $q->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('pekerjaanIbu', function ($q) use ($search) {
                                $q->where('nama', 'like', "%{$search}%");
                            })
                            ->orWhereHas('penghasilanOrtu', function ($q) use ($search) {
                                $q->where('nama', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter by date range
        if (isset($filters['start_date']) && $filters['start_date'] !== '') {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (isset($filters['end_date']) && $filters['end_date'] !== '') {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Filter by jenjang_sekolah
        if (isset($filters['jenjang_sekolah']) && $filters['jenjang_sekolah'] !== '') {
            $query->where('jenjang_sekolah', $filters['jenjang_sekolah']);
        }

        // Sorting functionality
        if (isset($filters['sort_by']) && $filters['sort_by'] !== '') {
            $sortField = $filters['sort_by'];
            $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';
            $query->orderBy($sortField, $sortDirection);
        } else {
            // Default sorting by created_at in descending order
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->with([
            'jurusan1',
            'biodataOrtu.pekerjaanAyah',
            'biodataOrtu.pekerjaanIbu',
            'biodataOrtu.penghasilanOrtu'
        ])->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }
}
