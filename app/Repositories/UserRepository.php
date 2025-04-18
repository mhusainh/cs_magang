<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\PesertaPpdb;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function __construct(private User $model) {}

    public function create(array $data): User
    {
        return $this->model->create($data);
    }

    public function findById(int $id): ?User
    {
        return $this->model->where('id', $id)->first();
    }

    public function findByPhone(string $no_telp): ?User
    {
        return $this->model->where('no_telp', $no_telp)->first();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(User $user): bool
    {
        return $user->delete();
    }
    public function findByIdCard(int $id):?user
    {
        return $this->model->with(
            'peserta',
            'progressUser',
            'pesan'
        )->where('id', $id)->first();
    }

}
