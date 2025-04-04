<?php

namespace App\Http\Controllers;

use App\DTO\PesertaDTO;
use App\Services\PesertaService;
use App\Traits\ApiResponse;
use App\Http\Requests\Peserta\CreatePesertaRequest;
use App\Http\Requests\Peserta\InputFormPesertaRequest;
use App\Http\Requests\Peserta\UpdatePesertaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesertaController extends Controller
{
    use ApiResponse;

    public function __construct(private PesertaService $pesertaService) {}

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
            return $this->error($result['message'], 404);
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
            $request->validated('no_telp'),
            $request->validated('jenis_kelamin'),
            $request->validated('jenjang_sekolah'),
            $request->validated('nisn'),
            $request->validated('tempat_lahir'),
            $request->validated('tanggal_lahir'),
            $request->validated('alamat'),
            $request->validated('jurusan1_id'),
            $request->validated('jurusan2_id')
        );

        $peserta = $this->pesertaService->getByUserId(Auth::user()->id);

        if (!$peserta['success']) {
            return $this->error($peserta['message'], 404);
        }
        
        $result = $this->pesertaService->update($peserta['data']->id, $data);

        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($data, $result['message'], 201);
    }

    public function inputFormPeserta(InputFormPesertaRequest $request)
    {
        $data = PesertaDTO::inputFormPesertaDTO(
            $request->validated('nisn'),
            $request->validated('tempat_lahir'),
            $request->validated('tanggal_lahir'),
            $request->validated('alamat'),
            $request->validated('jurusan1_id'),
            $request->validated('jurusan2_id')
        );

        $peserta = $this->pesertaService->getByUserId(Auth::user()->id);

        if (!$peserta['success']) {
            return $this->error($peserta['message'], 404);
        }
        
        $result = $this->pesertaService->update($peserta['data']->id, $data);

        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success($data, $result['message'], 201);
    }

    public function delete(int $id)
    {
        $result = $this->pesertaService->delete($id);

        if (!$result['success']) {
            return $this->error($result['message'], 404);
        }

        return $this->success(null, $result['message'], 204);
    }

    public function getAll()
    {
        $result = $this->pesertaService->getAll();

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
