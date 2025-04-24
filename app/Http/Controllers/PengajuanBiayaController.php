<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PesertaService;
use Illuminate\Support\Facades\Auth;
use App\Services\PengajuanBiayaService;
use App\Http\Requests\PengajuanBiaya\SppRequest;
use App\Http\Requests\PengajuanBiaya\WakafRequest;
use App\Http\Requests\PengajuanBiaya\CreateRequest;
use App\Http\Requests\PengajuanBiaya\BookVeeRequest;

class PengajuanBiayaController extends Controller
{
    use ApiResponse;

    public function __construct(
        private PengajuanBiayaService $pengajuanBiayaService,
        private PesertaService $pesertaService
    ) {}

    public function create(CreateRequest $request)
    {
        $data = [
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

    public function update(CreateRequest $request, $id)
    {
        $data = [
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

    public function getOnTop()
    {
        $result = $this->pengajuanBiayaService->getOnTop();
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function reguler()
    {
        $biaya = $this->pengajuanBiayaService->getOnTop();
        if (!$biaya['success']) {
            return $this->error($biaya['message'], 422, null);
        }
        $result = $this->pesertaService->update(Auth::user()->id, ['wakaf' => $biaya['data']['nominal']]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function wakaf(WakafRequest $request)
    {
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

    public function bookVee(BookVeeRequest $request)
    {
        $result = $this->pesertaService->update(Auth::user()->id, ['book_vee' => $request->validated('book_vee')]);
        if (!$result['success']) {
            return $this->error($result['message'], 422, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
