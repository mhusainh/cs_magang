<?php

namespace App\Repositories;

use App\Models\KetentuanBerkas;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class KetentuanBerkasRepository
{  
    public function __construct(private KetentuanBerkas $model){}
    /**
     * Mendapatkan semua ketentuan berkas dengan fitur pencarian dan filter
     */
    public function getAllKetentuanBerkas(Request $request = null): array
    {
        $query = $this->model->query();

        // Search functionality
        if ($request && $request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('jenjang_sekolah', 'like', "%{$search}%");
            });
        }

        // Jenjang sekolah filter
        if ($request && $request->has('jenjang') && $request->jenjang != '') {
            $query->where('jenjang_sekolah', $request->jenjang);
        }

        // Get unique jenjang sekolah for filter dropdown
        $jenjangSekolah = $this->model->select('jenjang_sekolah')
            ->distinct()
            ->orderBy('jenjang_sekolah')
            ->pluck('jenjang_sekolah');

        // Get total items for current filter
        $totalItems = $query->count();

        // Paginate results with 10 items per page
        $perPage = $request && $request->has('per_page') ? $request->per_page : 10;
        $ketentuanBerkas = $query->paginate($perPage)->withQueryString();

        // Get current filter status
        $currentFilters = [
            'search' => $request ? ($request->search ?? '') : '',
            'jenjang' => $request ? ($request->jenjang ?? '') : '',
            'total_items' => $totalItems
        ];

        return [
            'ketentuan_berkas' => $ketentuanBerkas,
            'jenjang_sekolah' => $jenjangSekolah,
            'current_filters' => $currentFilters
        ];
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan jenjang sekolah
     */
    public function getKetentuanBerkasByJenjang($jenjangSekolah): Collection
    {
        return $this->model->where('jenjang_sekolah', $jenjangSekolah)->get();
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getKetentuanBerkasById($id): ?KetentuanBerkas
    {
        return $this->model->where('id', $id)->first();
    }

    /**
     * Membuat ketentuan berkas baru
     */
    public function createKetentuanBerkas(array $data): KetentuanBerkas
    {
        return $this->model->create($data);
    }

    /**
     * Mengupdate ketentuan berkas
     */
    public function updateKetentuanBerkas(array $data): bool
    {
        return$this->model->update($data);      
    }

    public function deleteKetentuanBerkas($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }
}