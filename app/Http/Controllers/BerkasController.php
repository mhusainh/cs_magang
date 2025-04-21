<?php

namespace App\Http\Controllers;

use App\Models\PesertaPpdb;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\BerkasService;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Berkas\UploadBerkasRequest;
use App\Http\Requests\Berkas\UpdateBerkasRequest;
use App\Services\PesanService;
use Illuminate\Http\JsonResponse;

class BerkasController extends Controller
{
    use ApiResponse;

    public function __construct(
        private BerkasService $berkasService,
        private PesanService $pesanService
    ) {}

    /**
     * Get all berkas with search and filter functionality
     */
    public function getAllBerkas(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'ketentuan_berkas_id' => $request->ketentuan_berkas_id,
            'jenjang_sekolah' => $request->jenjang_sekolah,
            'nama_ketentuan' => $request->nama_ketentuan,
            'is_required' => $request->is_required,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->sort_order,
            'per_page' => $request->per_page
        ];

        $result = $this->berkasService->getAllBerkas($filters);
        if (!$result['success']) {
            return $this->error($result['message'], 404, null);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination']);
    }

    /**
     * Upload berkas
     * 
     * Memungkinkan pengguna untuk mengunggah banyak file dengan ketentuan berkas yang berbeda
     */
    public function uploadBerkas(UploadBerkasRequest $request)
    {
        $files = $request->validated('files');
        $ketentuanBerkasIds = $request->validated('ketentuan_berkas_ids');

        // Validasi data input
        if (empty($files) || empty($ketentuanBerkasIds)) {
            return $this->error('File dan ketentuan berkas tidak boleh kosong', 422, null);
        }

        // Pastikan setiap file memiliki ketentuan berkas yang sesuai
        if (count($files) !== count($ketentuanBerkasIds)) {
            return $this->error('Jumlah file tidak sesuai dengan jumlah ketentuan berkas', 422, null);
        }

        $results = [];
        $successCount = 0;
        $totalFiles = count($files);

        foreach ($files as $index => $file) {
            // Pastikan ketentuan berkas ID ada untuk file ini
            $ketentuanBerkasId = $ketentuanBerkasIds[$index] ?? null;
            if (is_null($ketentuanBerkasId)) {
                $results[] = [
                    'index' => $index,
                    'file_name' => $file->getClientOriginalName(),
                    'success' => false,
                    'message' => 'Ketentuan berkas tidak ditemukan untuk file ini',
                    'data' => null
                ];
                continue;
            }

            $data = [
                'file' => $file,
                'peserta_id' => Auth::user()->peserta->id,
                'ketentuan_berkas_id' => $ketentuanBerkasId,
            ];

            $result = $this->berkasService->uploadBerkas($data);
            
            if ($result['success']) {
                $successCount++;
            }
            
            $results[] = [
                'index' => $index,
                'file_name' => $file->getClientOriginalName(),
                'ketentuan_berkas_id' => $ketentuanBerkasId,
                'success' => $result['success'],
                'message' => $result['message'],
                'data' => $result['data']
            ];
        }

        // Jika semua file berhasil diupload
        if ($successCount === $totalFiles) {
            $dataPesan = [
                'user_id' => Auth::user()->id,
                'judul' => 'Upload Berkas',
                'deskripsi' => 'Halo ' . Auth::user()->peserta->nama . ', berkas Anda telah berhasil diunggah. Terima kasih!',
            ];

            $pesan = $this->pesanService->create($dataPesan);
            
            if (!$pesan['success']) {
                // Tetap kembalikan sukses meskipun pesan gagal dibuat
                return $this->success($results, 'Semua file berhasil diupload, tetapi notifikasi gagal dibuat', 200);
            }
            
            return $this->success($results, 'Semua file berhasil diupload', 200);
        }
        
        // Jika sebagian file berhasil diupload
        if ($successCount > 0) {
            return $this->success($results, $successCount . ' dari ' . $totalFiles . ' file berhasil diupload', 200);
        }
        
        // Jika semua file gagal diupload
        return $this->error('Semua file gagal diupload', 422, $results);
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
    
    /**
     * Update berkas
     */
    public function updateBerkas(UpdateBerkasRequest $request, int $id): JsonResponse
    {
        $file = $request->validated('file');
        $data = [
            'file' => $file,
            'ketentuan_berkas_id' => $request->validated('ketentuan_berkas_id'),
            'peserta_id' => Auth::user()->peserta->id
        ];
        
        $result = $this->berkasService->updateBerkas($id, $data);
        
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        
        return $this->success($result['data'], $result['message'], 200);
    }
}
