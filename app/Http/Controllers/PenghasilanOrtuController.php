<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenghasilanOrtu\CreateRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Services\PenghasilanOrtuService;
use App\Repositories\PenghasilanOrtuRepository;

class PenghasilanOrtuController extends Controller
{
    use ApiResponse;

    public function __construct(private PenghasilanOrtuService $penghasilanOrtuService){}

    public function create(CreateRequest $request){
        $result = $this->penghasilanOrtuService->create(['penghasilan_ortu' => $request->validated('penghasilan_ortu')]);
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 201);
    }

    public function getAll(){
        $result = $this->penghasilanOrtuService->getAll();
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function getById($id){
        $result = $this->penghasilanOrtuService->getById($id);
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function update($id, CreateRequest $request){
        $result = $this->penghasilanOrtuService->update($id, ['penghasilan_ortu' => $request->validated('penghasilan_ortu')]);
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete($id){
        $result = $this->penghasilanOrtuService->delete($id);
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
    
    public function getDeleted(){
        $result = $this->penghasilanOrtuService->getDeleted();
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function restore($id){
        $result = $this->penghasilanOrtuService->restore($id);
        if(!$result['success']){
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }
}
