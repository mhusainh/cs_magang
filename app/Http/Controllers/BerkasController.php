<?php

namespace App\Http\Controllers;

use App\Models\PesertaPpdb;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\BerkasService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Berkas\UploadBerkasRequest;

class BerkasController extends Controller
{
    use ApiResponse;

    public function __construct(
        private BerkasService $berkasService
    ) {}

    /**
     * Get all berkas with search and filter functionality
     */
    public function getAllBerkas(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'ketentuan_berkas_id' => $request->ketentuan_berkas_id
        ];

        $result = $this->berkasService->getAllBerkas($filters, 9);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    /**
     * Upload berkas
     */
    public function uploadBerkas(UploadBerkasRequest $request)
    {
        $files = $request->validated('files');
        $ketentuanBerkasIds = $request->validated('ketentuan_berkas_ids');

        if (count($files) !== count($ketentuanBerkasIds)) {
            return $this->error('Jumlah file tidak sesuai dengan jumlah ketentuan berkas', 422, null);
        }

        $results = [];
        foreach ($files as $index => $file) {
            $data = [
                'file' => $file,
                'peserta_id' => Auth::user()->peserta->id,
                'ketentuan_berkas_id' => $ketentuanBerkasIds[$index],
            ];

            $result = $this->berkasService->uploadBerkas($data);
            $results[] = [
                'ketentuan_berkas_id' => $ketentuanBerkasIds[$index],
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data']
            ];
        }

        $hasError = collect($results)->contains('success', false);
        if ($hasError) {
            return $this->error('Beberapa file gagal diupload', 422, $results);
        }

        return $this->success($results, 'Semua file berhasil diupload', 200);
    }

    /**
     * Menghapus berkas
     */
    public function deleteBerkas($id)
    {
        $result = $this->berkasService->deleteBerkas($id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
