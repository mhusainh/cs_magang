<?php

namespace App\Repositories;

use App\Models\Transaksi;
use Illuminate\Database\Eloquent\Collection;

class TransaksiRepository
{
    public function __construct(private Transaksi $model) {}

    public function getAll(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
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

    public function findBookVeeWithPeserta()
    {
        return $this->model
            ->join('tagihans', 'transaksis.tagihan_id', '=', 'tagihans.id')
            ->join('peserta_ppdbs', 'tagihans.user_id', '=', 'peserta_ppdbs.user_id')
            ->where('tagihans.nama_tagihan', 'book_vee')
            ->select(
                'transaksis.*',
                'peserta_ppdbs.id as peserta_id',
                'peserta_ppdbs.nama as peserta_nama',
                'peserta_ppdbs.wakaf as wakaf'
            )
            ->orderBy('wakaf', 'desc')
            ->orderBy('transaksis.created_at', 'asc')
            ->paginate(10);
    }

    public function findById(int $id)
    {
        return $this->model->where('id', $id)->first();
    }
    public function create(array $data)
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
}
