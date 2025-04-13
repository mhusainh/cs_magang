<?php

namespace App\Repositories;

use App\Models\Berkas;
use Illuminate\Support\Collection;

class BerkasRepository
{
    public function __construct(private Berkas $model) {}
    /**
     * Mendapatkan semua ketentuan berkas
     */
    public function getAllBerkas(Berkas $request)
    {
        $query = Berkas::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('kategori', $request->category);
        }


        // Get total items for current filter
        $totalItems = $query->count();

        // Paginate results with 9 items per page
        $barangs = $query->paginate(9)->withQueryString();

        // Get current filter status
        $currentFilters = [
            'search' => $request->search ?? '',
        ];


    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getBerkasById($id): ?Berkas
    {
        return $this->model->where('id', $id)->first();
    }


    /**
     * Mendapatkan berkas berdasarkan peserta ID dan ketentuan berkas ID
     */
    public function getBerkasByPesertaAndKetentuanId($pesertaId, $ketentuanBerkasId): Berkas
    {
        return $this->model->where('peserta_id', $pesertaId)
            ->where('kententuan_berkas_id', $ketentuanBerkasId)
            ->first();
    }

    /**
     * Membuat berkas baru
     */
    public function createBerkas(array $data): Berkas
    {
        return $this->model->create($data);
    }

    /**
     * Mengupdate berkas berdasarkan ID
     */
    public function updateBerkas($id, array $data): bool
    {
        return $this->model->update($data);
    }
    /**
     * Menghapus berkas
     */
    public function deleteBerkas($id): bool
    {
        return $this->model->where('id', $id)->delete();
    }
}
