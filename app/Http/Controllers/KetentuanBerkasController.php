<?php

namespace App\Http\Controllers;

use App\DTO\KetentuanBerkasDTO;
use App\Services\KetentuanBerkasService;
use App\Traits\ApiResponse;
use App\Http\Requests\Berkas\KetentuanBerkasRequest;
use Illuminate\Support\Facades\Auth;

class KetentuanBerkasController extends Controller
{
    use ApiResponse;

    public function __construct(private KetentuanBerkasService $ketentuanBerkasService) {}

    /**
     * Mendapatkan semua ketentuan berkas dengan fitur pencarian dan filter
     */
    public function getAll(\Illuminate\Http\Request $request)
    {
        $result = $this->ketentuanBerkasService->getAllKetentuanBerkas($request);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
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
}