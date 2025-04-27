<?php

namespace App\Repositories;

use App\Models\Jurusan;
use Illuminate\Database\Eloquent\Collection;

class JurusanRepository
{
    public function __construct(private Jurusan $model) {}

    public function findById(int $id): ?Jurusan
    {
        return $this->model->where('id', $id)->first();
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

    public function getAll(): Collection
    {
        return $this->model->all();
    }

} 
