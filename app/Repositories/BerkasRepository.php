<?php

namespace App\Repositories;

use App\Models\Berkas;
use Illuminate\Support\Collection;

class BerkasRepository
{
    public function __construct(private Berkas $model) {}
    /**
     * Mendapatkan semua ketentuan berkas
     */
    public function getAllBerkas(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_file', 'like', "%{$search}%")
                    ->orWhereHas('ketentuanBerkas', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('peserta', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%")
                            ->orWhere('nisn', 'like', "%{$search}%")
                            ->orWhere('no_telp', 'like', "%{$search}%");
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

        // Filter by ketentuan berkas
        if (isset($filters['ketentuan_berkas_id']) && $filters['ketentuan_berkas_id'] !== '') {
            $query->where('ketentuan_berkas_id', $filters['ketentuan_berkas_id']);
        }

        // Filter by jenjang sekolah
        if (isset($filters['jenjang_sekolah']) && $filters['jenjang_sekolah'] !== '') {
            $query->whereHas('ketentuanBerkas', function ($q) use ($filters) {
                $q->where('jenjang_sekolah', $filters['jenjang_sekolah']);
            });
        }

        // Filter by nama ketentuan
        if (isset($filters['nama_ketentuan']) && $filters['nama_ketentuan'] !== '') {
            $query->whereHas('ketentuanBerkas', function ($q) use ($filters) {
                $q->where('nama', 'like', "%{$filters['nama_ketentuan']}%");
            });
        }

        // Filter by is_required
        if (isset($filters['is_required']) && $filters['is_required'] !== '') {
            $query->whereHas('ketentuanBerkas', function ($q) use ($filters) {
                $q->where('is_required', $filters['is_required']);
            });
        }

        // Sorting functionality
        if (isset($filters['sort_by']) && $filters['sort_by'] !== '') {
            $sortField = $filters['sort_by'];
            $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';

            // Handle sorting for related fields
            if (strpos($sortField, '.') !== false) {
                [$relation, $field] = explode('.', $sortField);
                $query->join($relation, $relation . '.id', '=', 'berkas.' . $relation . '_id')
                    ->orderBy($relation . '.' . $field, $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            // Default sorting by created_at in descending order
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->with([
            'ketentuanBerkas' => function ($query) {$query->withTrashed();},
            'peserta' => function ($query) {$query->withTrashed();}
        ])->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getBerkasById($id): ?Berkas
    {
        return $this->model->where('id', $id)->first();
    }

    public function getBerkasByPesertaId($id): ?Berkas
    {
        return $this->model->where('peserta_id', $id)->first();
    }
    /**
     * Mendapatkan berkas berdasarkan peserta ID dan ketentuan berkas ID
     */
    public function getBerkasByPesertaAndKetentuanId($pesertaId, $ketentuanBerkasId): ?Berkas
    {
        return $this->model->where('peserta_id', $pesertaId)
            ->where('ketentuan_berkas_id', $ketentuanBerkasId)
            ->first();
    }

    /**
     * Membuat berkas baru
     */
    public function createBerkas(array $data): Berkas
    {
        return $this->model->create($data);
    }

    /**
     * Mengupdate berkas berdasarkan ID
     */
    public function updateBerkas($id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }
    /**
     * Menghapus berkas
     */
    public function deleteBerkas($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }
}
