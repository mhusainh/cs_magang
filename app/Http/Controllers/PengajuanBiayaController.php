<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PesertaService;
use App\Services\TagihanService;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Auth;
use App\Services\ProgressUserService;
use App\Services\PengajuanBiayaService;
use App\Http\Requests\PengajuanBiaya\SppRequest;
use App\Http\Requests\PengajuanBiaya\WakafRequest;
use App\Http\Requests\PengajuanBiaya\UpdateRequest;
use App\Http\Requests\PengajuanBiaya\CreateBookVeeRequest;
use App\Http\Requests\PengajuanBiaya\CreateRegulerRequest;

class PengajuanBiayaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PengajuanBiayaService $pengajuanBiayaService,
        private PesertaService $pesertaService,
        private TransaksiService $transaksiService,
        private TagihanService $tagihanService,
        private ProgressUserService $progressUserService,
    ) {}

    public function createBookVee(CreateBookVeeRequest $request)
    {
        $data = [
            'jurusan' => 'unggulan',
            'nominal' => $request->validated('nominal'),
        ];

        $bookVee = $this->pengajuanBiayaService->getBookVee();
        if ($bookVee['success']) {
            return $this->error('Book Vee sudah ada, silahkan edit yang sudah ada', 422, null);
        }

        $result = $this->pengajuanBiayaService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function createReguler(CreateRegulerRequest $request)
    {
        $data = [
            'jurusan' => 'reguler',
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
            'nominal' => $request->validated('nominal'),
        ];

        $result = $this->pengajuanBiayaService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getAll()
    {
        $result = $this->pengajuanBiayaService->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = [
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
            'jurusan' => $request->validated('jurusan'),
            'nominal' => $request->validated('nominal'),
        ];

        $result = $this->pengajuanBiayaService->update($id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete($id)
    {
        $result = $this->pengajuanBiayaService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function getById($id)
    {
        $result = $this->pengajuanBiayaService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getByUser()
    {
        $jurusan = Auth::user()->peserta->jurusan1->jurusan;
        $jenjang = Auth::user()->peserta->jenjang_sekolah;
        if ($jurusan != 'reguler') {
            $jurusan = 'unggulan';
            $jenjang = null;
        }
        $result = $this->pengajuanBiayaService->getByUser($jenjang, $jurusan);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function reguler()
    {
        if (Auth::user()->peserta->jurusan1->jurusan != 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya reguler', 422, null);
        }
        $biaya = $this->pengajuanBiayaService->getByUser(Auth::user()->peserta->jenjang_sekolah, Auth::user()->peserta->jurusan1->jurusan);
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }

        $data = [
            'pengajuan_biaya' => $biaya['data']['nominal'],
            'status' => 'diproses'
        ];
        $result = $this->pesertaService->update(Auth::user()->id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        $progressUser = $this->progressUserService->getByUserId(Auth::user()->id);
        if (!$progressUser['success']) {
            return $this->error($progressUser['message'], 404, null);
        }

        $updateProgress = $this->progressUserService->updateProgress($progressUser['data'], ['progress' => 3]);
        if (!$updateProgress['success']) {
            return $this->error($updateProgress['message'], 404, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function wakaf(WakafRequest $request)
    {
        if (Auth::user()->peserta->jurusan1->jurusan == 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya unggulan', 422, null);
        }

        $result = $this->pesertaService->update(Auth::user()->id, ['wakaf' => $request->validated('wakaf')]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function spp(SppRequest $request)
    {
        if (Auth::user()->peserta->jurusan1->jurusan == 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya unggulan', 422, null);
        }

        $data = [
            'spp' => $request->validated('spp'),
            'status' => 'diproses'
        ];

        $result = $this->pesertaService->update(Auth::user()->id, $data);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        $progressUser = $this->progressUserService->getByUserId(Auth::user()->id);
        if (!$progressUser['success']) {
            return $this->error($progressUser['message'], 404, null);
        }

        $updateProgress = $this->progressUserService->updateProgress($progressUser['data'], ['progress' => 3]);
        if (!$updateProgress['success']) {
            return $this->error($updateProgress['message'], 404, null);
        }

        return $this->success(null, $result['message'], 200);
    }

    public function bookVee()
    {
        if (Auth::user()->peserta->jurusan1->jurusan == 'reguler') {
            return $this->error('Anda tidak dapat mengajukan biaya unggulan', 422, null);
        }

        if (Auth::user()->tagihan->where('nama_tagihan', 'book_vee')->count() > 0) {
            return $this->error('Anda sudah mengajukan biaya book vee', 422, null);
        }

        $biaya = $this->pengajuanBiayaService->getBookVee();
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }

        $result = $this->pesertaService->update(Auth::user()->id, ['book_vee' => $biaya['data']['nominal']]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }

        $dataTagihan = [
            'user_id' => Auth::user()->id,
            'nama_tagihan' => 'book_vee',
            'total' => $biaya['data']->nominal,
        ];

        $tagihan = $this->tagihanService->create($dataTagihan);
        if (!$tagihan['success']) {
            return $this->error($tagihan['message'], 400, null);
        }

        return $this->success($tagihan['data'], $result['message'], 200);
    }
}
