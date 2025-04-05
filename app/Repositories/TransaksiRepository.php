<?php

namespace App\Repositories;

use App\Models\Transaksi;

class TransaksiRepository
{
    public function __construct(private Transaksi $model)
    {
    }

    public function getAll(int $userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    public function findUserById(int $id, int $userId)
    {
        return $this->model->where('id', $id)
                           ->where('user_id', $userId)
                           ->first();
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