<?php

namespace App\Http\Controllers;

use App\DTO\BiodataOrtuDTO;
use App\Http\Requests\BiodataOrtu\CreateRequest;
use App\Http\Requests\BiodataOrtu\UpdateRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\BiodataOrtuService;
use Illuminate\Support\Facades\Auth;

class BiodataOrtuController extends Controller
{
    use ApiResponse;
    public function __construct(private BiodataOrtuService $biodataOrtuService) {}

    public function getAll(){
        $result = $this->biodataOrtuService->getAll();
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getById($id){
        $result = $this->biodataOrtuService->getById($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function create(CreateRequest $request){
        $data = BiodataOrtuDTO::createBiodataOrtuDTO(
            Auth::user()->peserta->id,
            $request->validated('nama_ayah'),
            $request->validated('nama_ibu'),
            $request->validated('no_telp'),
            $request->validated('pekerjaan_ayah_id'),
            $request->validated('pekerjaan_ibu_id'),
            $request->validated('penghasilan_ortu_id')
        );

        $result = $this->biodataOrtuService->create($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function update(UpdateRequest $request, $id){
        $data = BiodataOrtuDTO::updateBiodataOrtuDTO(
            $id,
            $request->validated('nama_ayah'),
            $request->validated('nama_ibu'),
            $request->validated('no_telp'),
            $request->validated('pekerjaan_ayah_id'),
            $request->validated('pekerjaan_ibu_id'),
            $request->validated('penghasilan_ortu_id')
        );

        $result = $this->biodataOrtuService->update($data, $id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function updateByUser(UpdateRequest $request){
        $data = BiodataOrtuDTO::updateBiodataOrtuDTO(
            Auth::user()->peserta->biodataOrtu->id,
            $request->validated('nama_ayah'),
            $request->validated('nama_ibu'),
            $request->validated('no_telp'),
            $request->validated('pekerjaan_ayah_id'),
            $request->validated('pekerjaan_ibu_id'),
            $request->validated('penghasilan_ortu_id')
        );
        $result = $this->biodataOrtuService->update($data, Auth::user()->peserta->biodataOrtu->id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete($id){
        $result = $this->biodataOrtuService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getDeleted(){
        $result = $this->biodataOrtuService->getDeleted();
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function restore($id){
        $result = $this->biodataOrtuService->restore($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
