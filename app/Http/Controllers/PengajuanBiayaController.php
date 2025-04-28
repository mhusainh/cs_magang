<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PesertaService;
use App\Services\TransaksiService;
use Illuminate\Support\Facades\Auth;
use App\Services\PengajuanBiayaService;
use App\Http\Requests\PengajuanBiaya\SppRequest;
use App\Http\Requests\PengajuanBiaya\WakafRequest;
use App\Http\Requests\PengajuanBiaya\CreateRequest;
use App\Http\Requests\PengajuanBiaya\UpdateRequest;
use App\Http\Requests\PengajuanBiaya\BookVeeRequest;

class PengajuanBiayaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PengajuanBiayaService $pengajuanBiayaService,
        private PesertaService $pesertaService,
        private TransaksiService $transaksiService,
    ) {}

    public function create(CreateRequest $request)
    {
        $data = [
            'jurusan' => $request->validated('jurusan'),
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
            'jurusan' => $request->validated('jurusan'),
            'jenjang_sekolah' => $request->validated('jenjang_sekolah'),
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
        return $this->success($result['data'], $result['message'], 200);
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
        $result = $this->pengajuanBiayaService->getByUser(Auth::user()->jenjang_sekolah, Auth::user()->jurusan);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function reguler()
    {
        $biaya = $this->pengajuanBiayaService->getByUser(Auth::user()->jenjang_sekolah, Auth::user()->jurusan);
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }
        $result = $this->pesertaService->update(Auth::user()->id, ['pengajuan_biaya' => $biaya['data']['nominal']]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function wakaf(WakafRequest $request)
    {
        $bookVee = $this->transaksiService->getUserBookVee();
        if (!$bookVee['success']) {
            return $this->error('Silahkan membayar Book Vee terlebih dahulu', 422, null);
        }
        $result = $this->pesertaService->update(Auth::user()->id, ['wakaf' => $request->validated('wakaf')]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function spp(SppRequest $request)
    {
        $result = $this->pesertaService->update(Auth::user()->id, ['spp' => $request->validated('spp')]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function bookVee()
    {
        $biaya = $this->pengajuanBiayaService->getByUser(Auth::user()->jenjang_sekolah, Auth::user()->jurusan);
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }
        $result = $this->pesertaService->update(Auth::user()->id, ['book_vee' => $biaya['data']['nominal']]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
