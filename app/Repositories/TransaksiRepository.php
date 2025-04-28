<?php

namespace App\Repositories;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class TransaksiRepository
{
    public function __construct(private Transaksi $model) {}

    public function getAll(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (isset($filters['search']) && $filters['search'] !== '') {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('created_time', 'like', "%{$search}%")
                    ->orWhere('total', 'like', "%{$search}%")
                    ->orWhere('va_number', 'like', "%{$search}%")
                    ->orWhere('transaction_qr_id', 'like', "%{$search}%")
                    ->orWhereHas('tagihan', function ($q) use ($search) {
                        $q->where('nama_tagian', 'like', "%{$search}%")
                            ->orWhereHas('user.peserta', function ($q) use ($search) {
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

        // Filter by status
        if (isset($filters['status']) && $filters['status'] !== '') {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['method']) && $filters['method'] !== '') {
            $query->where('method', $filters['method']);
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

    public function getByUserId(int $iUserId)
    {
        return $this->model->where('user_id', $iUserId)->get();
    }

    public function findUserId(int $userId, array $filters = [])
    {
        $query = $this->model->where('user_id', $userId);

        if (isset($filters['start_date']) && $filters['start_date'] !== '') {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date']) && $filters['end_date'] !== '') {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());
    }

    public function findBookVeeWithPeserta(?int $jurusan1_id = null)
    {
        $query = $this->model
            ->join('tagihans', 'transaksis.tagihan_id', '=', 'tagihans.id')
            ->join('peserta_ppdbs', 'tagihans.user_id', '=', 'peserta_ppdbs.user_id')
            ->where('tagihans.nama_tagihan', 'book_vee');

        if ($jurusan1_id !== null) {
            $query->where('peserta_ppdbs.jurusan1_id', $jurusan1_id);
        }

        return $query->select(
            'transaksis.*',
            'peserta_ppdbs.id as peserta_id',
            'peserta_ppdbs.nama as peserta_nama',
            'peserta_ppdbs.wakaf as wakaf'
        )
            ->orderBy('wakaf', 'desc')
            ->orderBy('transaksis.created_at', 'asc');
    }

    public function findById(int $id)
    {
        return $this->model->where('id', $id)->first();
    }

    public function findByQrId(string $transactionQrId)
    {
        return $this->model->where('transaction_qr_id', $transactionQrId)->first();
    }

    public function create(array $data): Transaksi
    {
        return $this->model->create($data);
    }

    public function update(Transaksi $transaksi, array $data)
    {
        return $transaksi->update($data);
    }

    public function delete(int $id)
    {
        return $this->model->where('id', $id)->delete();
    }

    public function findBookVee()
    {
        return $this->model
            ->whereHas('tagihan', function ($query) {
                $query->where('nama_tagihan', 'book_vee');
            })
            ->where('user_id', Auth::user()->id)
            ->get();
    }
}
