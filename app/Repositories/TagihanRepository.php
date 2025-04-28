<?php

namespace App\Repositories;

use App\Models\Tagihan;
use Illuminate\Database\Eloquent\Collection;

class TagihanRepository
{
    public function __construct(private Tagihan $model)
    {
    }

    public function findById(int $id): ?Tagihan
    {
        return $this->model->where('id', $id)->first();
    }
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(Tagihan $tagihan, array $data)
    {
        return $tagihan->update($data);
    }

    public function delete(Tagihan $tagihan)
    {
        return $tagihan->delete();
    }

    public function vaNumberExists(string $vaNumber, ?int $excludeId = null): bool
    {
        $query = $this->model->where('va_number', $vaNumber);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public function getAll(array $filters)
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama_tagihan', 'like', "%{$search}%")
                    ->orWhere('total', 'like', "%{$search}%")
                    ->orWhere('va_number', 'like', "%{$search}%")
                    ->orWhere('transaction_qr_id', 'like', "%{$search}%")
                    ->orWhere('created_time', 'like', "%{$search}%")
                    ->orWhereHas('user.peserta', function ($q) use ($search) {
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

        // Filter by status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }
        
        if (isset($filters['nama_tagihan']) && $filters['nama_tagihan'] !== '') {
            $query->where('nama_tagihan', $filters['nama_tagihan']);
        }

        // Filter by total range
        if (isset($filters['total_min']) && $filters['total_min'] !== '') {
            $query->where('total', '>=', $filters['total_min']);
        }
        if (isset($filters['total_max']) && $filters['total_max'] !== '') {
            $query->where('total', '<=', $filters['total_max']);
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
        return $this->model->where('user_id', $userId)->get();
    }

    public function getByQrData(string $qrData): ?Tagihan
    {
        return $this->model->where('qr_data', $qrData)->first();
    }

    public function getByQrId(string $qrId): ?Tagihan
    {
        return $this->model->where('transaction_qr_id', $qrId)->first();
    }
}
