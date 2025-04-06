<?php

namespace App\Repositories;

use App\Models\ProgressUser;
use Illuminate\Database\Eloquent\Collection;

class ProgressUserRepository
{
    public function __construct(private ProgressUser $model) {}

    public function create(array $data): ProgressUser
    {
        return $this->model->create($data);
    }

    public function update(ProgressUser $progressUser, array $data): bool
    {
        return $progressUser->update($data);
    }

    public function findById(int $id):?ProgressUser
    {
        return $this->model->where('id', $id)->first();
    }

    public function findByUserId(int $user_id):?ProgressUser
    {
        return $this->model->where('user_id', $user_id)->first();
    }

    public function delete(ProgressUser $progressUser): bool
    {
        return $progressUser->delete();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }
} 