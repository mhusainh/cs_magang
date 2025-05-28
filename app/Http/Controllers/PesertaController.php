<?php

namespace App\Http\Controllers;

use App\DTO\PesertaDTO;
use App\Http\Requests\Peserta\UpdateStatusRequest;
use App\Traits\ApiResponse;
use App\DTO\ProgressUserDTO;
use Illuminate\Http\Request;
use App\Services\PesanService;
use App\Services\PesertaService;
use Illuminate\Support\Facades\Auth;
use App\Services\ProgressUserService;
use App\Http\Requests\Peserta\UpdatePesertaRequest;
use App\Http\Requests\Peserta\InputFormPesertaRequest;
use App\Http\Requests\Peserta\UpdateNisRequest;
use Illuminate\Http\JsonResponse;

class PesertaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PesertaService $pesertaService,
        private ProgressUserService $progressUserService,
        private PesanService $pesanService
    ) {}

    // public function create(CreatePesertaRequest $request)
    // {
    //     $data = PesertaDTO::createPesertaDTO(
    //         $request->validated('user_id'),
    //         $request->validated('nama'),
    //         $request->validated('no_telp'),
    //         $request->validated('jenis_kelamin'),
    //         $request->validated('jenjang_sekolah')
    //     );

    //     $result = $this->pesertaService->create($data);

    //     if (!$result['success']) {
    //         return $this->error($result['message'], 422);
    //     }

    //     return $this->success($result['data'], $result['message'], 201);
    // }

    public function getById(int $id)
    {
        $result = $this->pesertaService->getById($id);

        if (!$result['success']) {
            return $this->error($result['message'], $result['code']);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUserId(int $userId)
    {
        $result = $this->pesertaService->getByUserId($userId);

        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUser()
    {
        $result = $this->pesertaService->getByUserId(Auth::user()->id);
        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function updateByUser(UpdatePesertaRequest $request)
    {
        $data = PesertaDTO::updatePesertaByUserDTO(
            $request->validated('nama'),
            $request->validated('jenis_kelamin'),
            $request->validated('jenjang_sekolah'),
            $request->validated('nisn'),
            $request->validated('tempat_lahir'),
            $request->validated('tanggal_lahir'),
            $request->validated('alamat'),
            $request->validated('asal_sekolah'),
            $request->validated('jurusan1_id'),
        );

        $result = $this->pesertaService->update(Auth::user()->id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Formulir Pendaftaran Peserta',
            'deskripsi' => 'Data pendaftaran PPDB Anda telah diperbarui. Mohon periksa kembali kebenaran data yang telah diubah.'
        ];
        $pesan = $this->pesanService->create($dataPesan);
        if (!$pesan['success']) {
            return $this->error($pesan['message'], 400, null);
        }

        return $this->success($data, $result['message'], 200);
    }

    public function update(UpdatePesertaRequest $request, int $id)
    {
        $data = PesertaDTO::updatePesertaByUserDTO(
            $request->validated('nama'),
            $request->validated('jenis_kelamin'),
            $request->validated('jenjang_sekolah'),
            $request->validated('nisn'),
            $request->validated('tempat_lahir'),
            $request->validated('tanggal_lahir'),
            $request->validated('alamat'),
            $request->validated('asal_sekolah'),
            $request->validated('jurusan1_id'),
        );
        $result = $this->pesertaService->update($id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Formulir Pendaftaran Peserta',
            'deskripsi' => 'Data pendaftaran PPDB Anda telah diperbarui. Mohon periksa kembali kebenaran data yang telah diubah.'
        ];
        $pesan = $this->pesanService->create($dataPesan);
        if (!$pesan['success']) {
            return $this->error($pesan['message'], 400, null);
        }

        return $this->success($data, $result['message'], 200);
    }

    public function inputFormPeserta(InputFormPesertaRequest $request)
    {
        $data = PesertaDTO::inputFormPesertaDTO(
            $request->validated('nisn'),
            $request->validated('tempat_lahir'),
            $request->validated('tanggal_lahir'),
            $request->validated('alamat'),
            $request->validated('asal_sekolah'),
            $request->validated('jurusan1_id'),
        );
        $progressData = ProgressUserDTO::createProgressUserDTO(
            user_id: Auth::user()->id,
            progress: 1
        );

        $peserta = $this->pesertaService->getByUserId(Auth::user()->id);
        if (!$peserta['success']) {
            return $this->error($peserta['message'], 400);
        }

        $result = $this->pesertaService->update($peserta['data']->id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        $progress = $this->progressUserService->create($progressData);
        if (!$progress['success']) {
            return $this->error($progress['message'], 400);
        }

        $dataPesan = [
            'user_id' => Auth::user()->id,
            'judul' => 'Formulir Pendaftaran Peserta',
            'deskripsi' => 'Selamat! Anda telah menyelesaikan tahap pengisian formulir pendaftaran PPDB. Langkah selanjutnya adalah mengunggah berkas persyaratan yang dibutuhkan.'
        ];
        $pesan = $this->pesanService->create($dataPesan);
        if (!$pesan['success']) {
            return $this->error($pesan['message'], 400, null);
        }

        return $this->success($data, $result['message'], 200);
    }

    public function delete(int $id)
    {
        $result = $this->pesertaService->delete($id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function getAll(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'jenjang_sekolah' => $request->jenjang_sekolah,
            'angkatan' => $request->angkatan,
            'status' => $request->status, // Tambahkan filter 'status'
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'per_page' => $request->per_page
        ];
        $result = $this->pesertaService->getAll($filters);

        if (!$result['success']) {
            return $this->error($result['message'], $result['code']);
        }

        return $this->success($result['data'], $result['message'], $result['code'], $result['pagination'], $result['current_filters']);
    }
    public function updateNis(int $id, UpdateNisRequest $request)
    {
        $result = $this->pesertaService->update($id, ['nis' => $request->validated('nis')]);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success(null, $result['message'], 200);
    }
    public function updateStatus(int $id, UpdateStatusRequest $request)
    {
        $result = $this->pesertaService->update($id, ['status' => $request->validated('status')]);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        $dataPesan = [
            'user_id' => $result['user_id'],
            'judul' => 'Pengumuman Hasil Verifikasi Berkas',
            'deskripsi' => $request->validated('status') === 'diterima' ? 'Selamat ' . $result['nama_peserta'] . '! Berkas pendaftaran PPDB Anda telah diterima dan diverifikasi dengan baik.'
                : 'Maaf ' . $result['nama_peserta'] . ', anda dinyatakan tidak lolos seleksi ppdb!'
        ];

        $pesan = $this->pesanService->create($dataPesan);

        if (!$pesan['success']) {
            // Tetap kembalikan sukses meskipun pesan gagal dibuat
            return $this->success($result, 'Semua file berhasil diupload, tetapi notifikasi gagal dibuat', 200);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function getDeleted(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'jenjang_sekolah' => $request->jenjang_sekolah,
            'angkatan' => $request->angkatan,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'per_page' => $request->per_page
        ];
        $result = $this->pesertaService->getDeleted($filters);

        if (!$result['success']) {
            return $this->error($result['message'], $result['code']);
        }

        return $this->success($result['data'], $result['message'], $result['code'], $result['pagination'], $result['current_filters']);
    }

    public function restore(int $id)
    {
        $result = $this->pesertaService->restore($id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getPeringkatByUser(): JsonResponse
    {
        $jenjang_sekolah = Auth::user()->peserta->jenjang_sekolah;
        $angkatan = Auth::user()->peserta->angkatan;
        $result = $this->pesertaService->getPeringkat(Auth::user()->peserta->jurusan1_id, $jenjang_sekolah, $angkatan);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getPeringkat(Request $request)
    {
        $result = $this->pesertaService->getPeringkat($request->jurusan_id, $request->jenjang_sekolah, $request->angkatan);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
