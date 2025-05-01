<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;

class MediaRepository
{
    public function __construct(private Media $model) {}

    public function findById(int $id): ?Media
    {
        return $this->model->where('id', $id)->first();
    }

    public function findByUser(string $nama, string $jenjang_sekolah, string $jurusan = null): Collection 
    { 
        $query = $this->model->where('nama', $nama)
            ->where('jenjang_sekolah', $jenjang_sekolah);
        
        if ($jurusan !== null) {
            $query->where('jurusan', $jurusan);
        }
        
        return $query->get();
    }

    public function create(array $data): Media
    {
        return $this->model->create($data);
    }

    public function update(?object $cloudinary, array $data, int $id): bool
    {
        if ($cloudinary) {
            $data['url'] = $cloudinary->getSecurePath();
            $data['public_id'] = $cloudinary->getPublicId();
        }
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(Media $media): bool
    {
        return $media->delete();
    }

    public function getAll(array $filters = [])
    {
        $query = $this->model->query();

        // Search functionality
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('jenjang_sekolah', 'like', "%{$search}%");
            });
        }

        // Filter by jenjang_sekolah
        if (!empty($filters['jenjang_sekolah'])) {
            $query->where('jenjang_sekolah', $filters['jenjang_sekolah']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', $filters['end_date']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDirection = strtolower($filters['sort_direction'] ?? 'desc') === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        return $query->paginate($filters['per_page'] ?? 10);
    }
    public function validation(array $media): array
    {
        return $this->model->where('nama', $media['nama'])
                            ->where('jenjang_sekolah', $media['jenjang_sekolah'])
                            ->where('jurusan', $media['jurusan'])->get();
    }
}
