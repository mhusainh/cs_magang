<?php

namespace App\Repositories;

use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Collection;

class JurusanRepository
{
    public function __construct(private Jurusan $model) {}

    public function findById(int $id): ?Jurusan
    {
        return $this->model->withTrashed()->where('id', $id)->first();
    }

    public function findbyJenjangSekolah(string $jenjang_sekolah): Collection
    {
        return $this->model->where('jenjang_sekolah', $jenjang_sekolah)->get();
    }

    public function create(array $data): Jurusan
    {
        return $this->model->create($data);
    }

    public function update(Jurusan $jurusan, array $data): bool
    {
        return $jurusan->update($data);
    }

    public function delete(Jurusan $jurusan): bool
    {
        return $jurusan->delete();
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('jurusan', 'like', "%{$search}%")
                    ->orWhere('jenjang_sekolah', 'like', "%{$search}%");
            });
        }

        // Jenjang sekolah filter
        if (isset($filters['jenjang']) && $filters['jenjang'] !== '') {
            $query->where('jenjang_sekolah', $filters['jenjang']);
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

    public function getTrash(): Collection
    {
        return $this->model->onlyTrashed()->get();
    }

    public function restore(Jurusan $jurusan): bool
    {
        return $jurusan->restore();
    }
    
    public function validation(array $data): Collection
    {
        $query = $this->model->where('jurusan', $data['jurusan'])
                            ->where('jenjang_sekolah', $data['jenjang_sekolah']);
        return $query->get();
    }
} 
