<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Services\UserService;
use App\Traits\ApiResponse;
use App\Http\Requests\User\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService) {}

    public function update(UpdateRequest $request, int $id)
    {
        try {
            $data = UserDTO::UserUpdateDTO(
                $id,
                $request->validated('no_telp')
            );

            $result = $this->userService->update($data);

            if (!$result['success']) {
                return $this->error($result['message'], 404);
            }

            return $this->success($result['data'] ?? null, $result['message'], 200);
        } catch (ValidationException $e) {
            return $this->error('Terjadi kesalahan saat mempengisi data user', 500);
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat memperbarui user', 500);
        }
    }

    public function delete(int $id)
    {
        $result = $this->userService->delete($id);

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
            'status' => $request->status,
            'per_page' => $request->per_page,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by
        ];
        $result = $this->userService->getAll($filters);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function getById(int $id)
    {
        $result = $this->userService->getById($id);

        if (!$result['success']) {
            return $this->error($result['message'], 200);
        }

        return $this->success($result['data'], $result['message'], 200);
    }

    public function progressPayment(int $id)
    {
        $result = $this->userService->progressPayment($id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
    public function getTrash(Request $request)
    {
        $filters = [
            'search' => $request->search,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'per_page' => $request->per_page,
            'sort_by' => $request->sort_by,
            'sort_direction' => $request->order_by
        ];
        $result = $this->userService->getDeleted($filters);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200, $result['pagination'], $result['current_filters']);
    }

    public function restore(int $id)
    {
        $result = $this->userService->restore($id);

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
