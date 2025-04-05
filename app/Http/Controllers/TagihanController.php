<?php

namespace App\Http\Controllers;

use App\DTO\TagihanDTO;
use App\Http\Requests\Tagihan\CreateRequest;
use App\Http\Requests\Tagihan\UpdateRequest;
use App\Services\TagihanService;
use App\Services\PesertaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TagihanController extends Controller
{
    public function __construct(private TagihanService $tagihanService, private PesertaService $pesertaService)
    {
    }

    public function getAll(): JsonResponse
    {
        $result = $this->tagihanService->getAll(Auth::user()->id);
        return $this->response($result);
    }

    public function getById(int $id): JsonResponse
    {
        $userId = Auth::user()->id;
        $result = $this->tagihanService->getById($id, $userId);
        return $this->response($result);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = TagihanDTO::createTagihanDTO(
            $request->validated('user_id'),
            $request->validated('nama_tagihan'),
            $request->validated('total'),
        );

        $result = $this->tagihanService->create($data);
        return $this->response($result, 201);
    }

    public function update(UpdateRequest $request, int $id): JsonResponse
    {
        $data = TagihanDTO::updateTagihanDTO(
            $id,
            $request->validated('nama_tagihan'),
            $request->validated('total'),
            $request->validated('status'),
            $request->validated('va_number'),
            $request->validated('transaction_qr_id'),
            $request->validated('created_time')
        );

        $result = $this->tagihanService->update($data);
        return $this->response($result);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->tagihanService->delete($id);
        return $this->response($result);
    }
} 