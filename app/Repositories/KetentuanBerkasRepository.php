<?php

namespace App\Repositories;

use App\Models\KetentuanBerkas;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KetentuanBerkasRepository
{
    public function __construct(private KetentuanBerkas $model) {}
    /**
     * Mendapatkan semua ketentuan berkas dengan fitur pencarian dan filter
     */
    public function getAllKetentuanBerkas(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jenjang_sekolah', 'like', "%{$search}%");
            });
        }

        // Jenjang sekolah filter
        if (isset($filters['jenjang']) && $filters['jenjang'] !== '') {
            $query->where('jenjang_sekolah', $filters['jenjang']);
        }

        // Is required filter
        if (isset($filters['is_required']) && $filters['is_required'] !== '') {
            $query->where('is_required', $filters['is_required']);
        }

        // Sorting functionality
        if (isset($filters['sort_by']) && $filters['sort_by'] !== '') {
            $sortField = $filters['sort_by'];
            $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';

            // Handle sorting for related field
            if (strpos($sortField, '.') !== false) {
                [$relation, $field] = explode('.', $sortField);
                $query->join($relation, $relation . '.id', '=', 'ketentuan_berkas.' . $relation . '_id')
                    ->orderBy($relation . '.' . $field, $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            // Default sorting by created_at in descending order
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan jenjang sekolah
     */
    public function getKetentuanBerkasByJenjang($jenjangSekolah): Collection
    {
        return $this->model->where('jenjang_sekolah', $jenjangSekolah)->get();
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getKetentuanBerkasById($id): ?KetentuanBerkas
    {
        return $this->model->withTrashed()->where('id', $id)->first();
    }

    /**
     * Membuat ketentuan berkas baru
     */
    public function createKetentuanBerkas(array $data): KetentuanBerkas
    {
        return $this->model->create($data);
    }

    /**
     * Mengupdate ketentuan berkas
     */
    public function updateKetentuanBerkas(array $data): bool
    {
        return $this->model->update($data);
    }

    public function deleteKetentuanBerkas($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    public function getTrash(array $filters = [])
    {
        $query = $this->model->onlyTrashed()->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jenjang_sekolah', 'like', "%{$search}%");
            });
        }

        // Jenjang sekolah filter
        if (isset($filters['jenjang']) && $filters['jenjang'] !== '') {
            $query->where('jenjang_sekolah', $filters['jenjang']);
        }

        // Is required filter
        if (isset($filters['is_required']) && $filters['is_required'] !== '') {
            $query->where('is_required', $filters['is_required']);
        }

        // Sorting functionality
        if (isset($filters['sort_by']) && $filters['sort_by'] !== '') {
            $sortField = $filters['sort_by'];
            $sortDirection = isset($filters['sort_direction']) && strtolower($filters['sort_direction']) === 'desc' ? 'desc' : 'asc';

            // Handle sorting for related field
            if (strpos($sortField, '.') !== false) {
                [$relation, $field] = explode('.', $sortField);
                $query->join($relation, $relation . '.id', '=', 'ketentuan_berkas.' . $relation . '_id')
                    ->orderBy($relation . '.' . $field, $sortDirection);
            } else {
                $query->orderBy($sortField, $sortDirection);
            }
        } else {
            // Default sorting by created_at in descending order
            $query->orderBy('created_at', 'desc');
        }

        $paginator = $query->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }

    public function restore(KetentuanBerkas $ketentuanBerkas): bool
    {
        return $ketentuanBerkas->restore();
    }
}
