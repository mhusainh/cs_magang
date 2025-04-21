<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Services\UserService;
use App\Traits\ApiResponse;
use App\Http\Requests\User\UpdateRequest;
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

    public function getAll()
    {
        $result = $this->userService->getAll();

        if (!$result['success']) {
            return $this->error($result['message'], 400);
        }

        return $this->success($result['data']);
    }

    public function getById(int $id)
    {
        $result = $this->userService->getById($id);

        if (!$result['success']) {
            return $this->error($result['message'], 200);
        }

        return $this->success($result['data'], $result['message'], 200);
    }
}
