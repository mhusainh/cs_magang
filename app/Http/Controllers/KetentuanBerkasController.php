<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\DTO\KetentuanBerkasDTO;
use Illuminate\Support\Facades\Auth;
use App\Services\KetentuanBerkasService;
use App\Http\Requests\Berkas\KetentuanBerkasRequest;
use App\Http\Requests\KetentuanBerkas\UpdateRequest;

class KetentuanBerkasController extends Controller
{
    use ApiResponse;

    public function __construct(private KetentuanBerkasService $ketentuanBerkasService) {}

    /**
     * Mendapatkan semua ketentuan berkas dengan fitur pencarian dan filter
     */
    public function getAll(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'jenjang' => $request->jenjang,
            'is_required' => $request->is_required,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'per_page' => $request->per_page
        ];

        $result = $this->ketentuanBerkasService->getAllKetentuanBerkas($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination']);
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan jenjang sekolah
     */
    public function getByJenjang()
    {
        $result = $this->ketentuanBerkasService->getKetentuanBerkasByJenjang(Auth::user()->peserta->jenjang_sekolah);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    /**
     * Mendapatkan ketentuan berkas berdasarkan ID
     */
    public function getById($id)
    {
        $result = $this->ketentuanBerkasService->getKetentuanBerkasById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    /**
     * Membuat ketentuan berkas baru
     */
    public function create(KetentuanBerkasRequest $request)
    {
        $data = KetentuanBerkasDTO::createKetentuanBerkasDTO(
            $request->validated('nama'),
            $request->validated('jenjang_sekolah'),
            $request->validated('is_required')
        );
        $result = $this->ketentuanBerkasService->createKetentuanBerkas($data);

        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 201);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = KetentuanBerkasDTO::createKetentuanBerkasDTO(
            $request->validated('nama'),
            $request->validated('jenjang_sekolah'),
            $request->validated('is_required')
        );
        $result = $this->ketentuanBerkasService->updateKetentuanBerkas($id, $data);

        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($data, $result['message'], 200);
    }

    /**
     * Menghapus ketentuan berkas
     */
    public function delete($id)
    {
        $result = $this->ketentuanBerkasService->deleteKetentuanBerkas($id);

        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function getDeleted(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'jenjang' => $request->jenjang,
            'is_required' => $request->is_required,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'per_page' => $request->per_page
        ];

        $result = $this->ketentuanBerkasService->getDeleted($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination']);
    }

    public function restore($id)
    {
        $result = $this->ketentuanBerkasService->restore($id);

        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
