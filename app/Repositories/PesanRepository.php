<?php

namespace App\Repositories;

use App\Models\Pesan;
use Illuminate\Database\Eloquent\Collection;

class PesanRepository
{
    public function __construct(private Pesan $model) {}

    public function findById(int $id): ?Pesan
    {
        return $this->model->where('id', $id)->first();
    }

    public function create(array $data): Pesan
    {
        return $this->model->create($data);
    }

    public function update(Pesan $pesan, array $data): bool
    {
        return $pesan->update($data);
    }

    public function delete(Pesan $pesan): bool
    {
        return $pesan->delete();
    }

    public function getAll(array $filters)
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('nama', 'like', "%{$search}%");
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

        // Filter by is_read
        if (isset($filters['is_read']) && $filters['is_read'] !== '') {
            $query->where('is_read', $filters['is_read']);
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

        $paginator = $query->paginate($filters['per_page'] ?? 10);
        return $paginator->appends(request()->query());
    }
    public function getByUserId(int $userId): Collection
    {
        return $this->model->where('user_id', $userId)->orderBy('created_at', 'desc')->get();
    }
}