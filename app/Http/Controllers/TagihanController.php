<?php

namespace App\Http\Controllers;

use App\DTO\TagihanDTO;
use App\Traits\ApiResponse;
use App\Services\PesertaService;
use App\Services\TagihanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Tagihan\CreateRequest;
use App\Http\Requests\Tagihan\UpdateRequest;

class TagihanController extends Controller
{
    use ApiResponse;

    public function __construct(private TagihanService $tagihanService, private PesertaService $pesertaService) {}

    public function getByUser(): JsonResponse
    {
        $result = $this->tagihanService->getByUserId(Auth::user()->id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function getAll(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'per_page' => $request->per_page,
            'nama_tagihan' => $request->nama,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'total_min' => $request->total_min,
            'total_max' => $request->total_max,
        ];

        $result = $this->tagihanService->getAll($filters);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function getById(int $id): JsonResponse
    {
        $result = $this->tagihanService->getById($id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function create(CreateRequest $request): JsonResponse
    {
        $data = TagihanDTO::createTagihanDTO(
            $request->validated('user_id'),
            $request->validated('nama_tagihan'),
            $request->validated('total'),
        );

        $result = $this->tagihanService->create($data);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 201);
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
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success($result['data'], $result['message'], 200);
    }

    public function delete(int $id): JsonResponse
    {
        $result = $this->tagihanService->delete($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success(null, $result['message'], 200);
    }

    public function getDeleted(Request $request): JsonResponse
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'per_page' => $request->per_page,
            'nama_tagihan' => $request->nama,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by,
            'total_min' => $request->total_min,
            'total_max' => $request->total_max,
        ];

        $result = $this->tagihanService->getDeleted($filters);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function restore(int $id): JsonResponse
    {
        $result = $this->tagihanService->restore($id);
        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }
        return $this->success(null, $result['message'], 200);
    }
}
