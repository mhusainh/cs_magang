<?php

namespace App\Http\Controllers;

use App\Models\Berkas;
use App\Models\PesertaPpdb;
use App\Services\BerkasService;
use App\Services\KetentuanBerkasService;
use App\Traits\ApiResponse;
use App\Http\Requests\Berkas\UploadBerkasRequest;
use App\Http\Requests\Berkas\UpdateBerkasRequest;
use Illuminate\Support\Facades\Auth;

class BerkasController extends Controller
{
    use ApiResponse;

    public function __construct(
        private BerkasService $berkasService,
        private KetentuanBerkasService $ketentuanService
    ) {}

    /**
     * Mendapatkan ketentuan berkas berdasarkan jenjang sekolah peserta
     */
    public function getKetentuanBerkas()
    {
        // Ambil jenjang sekolah dari peserta yang terkait dengan user yang login
        $peserta = PesertaPpdb::where('user_id', Auth::user()->id)->first();
        if (!$peserta) {
            return $this->error('Data peserta tidak ditemukan', 404, null);
        }

        $result = $this->ketentuanService->getKetentuanBerkasByJenjang($peserta->jenjang_sekolah);
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
     * Mendapatkan berkas peserta
     */
    public function getBerkas()
    {
        // Ambil peserta yang terkait dengan user yang login
        $peserta = PesertaPpdb::where('user_id', Auth::user()->id)->first();
        if (!$peserta) {
            return $this->error('Data peserta tidak ditemukan', 404, null);
        }

        $result = $this->berkasService->getBerkasByPesertaId($peserta->id);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    /**
     * Menghapus berkas
     */
    public function deleteBerkas($id)
    {
        // Cek apakah berkas milik peserta yang login
        $peserta = PesertaPpdb::where('user_id', Auth::user()->id)->first();
        if (!$peserta) {
            return $this->error('Data peserta tidak ditemukan', 404, null);
        }

        $berkas = Berkas::find($id);
        if (!$berkas) {
            return $this->error('Berkas tidak ditemukan', 404, null);
        }

        if ($berkas->peserta_id != $peserta->id) {
            return $this->error('Anda tidak memiliki akses untuk menghapus berkas ini', 403, null);
        }

        $result = $this->berkasService->deleteBerkas($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    /**
     * Admin: Mendapatkan berkas peserta berdasarkan ID peserta
     */
    public function getBerkasByPesertaId($pesertaId)
    {
        $result = $this->berkasService->getBerkasByPesertaId($pesertaId);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
