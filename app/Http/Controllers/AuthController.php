<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DTO\UserDTO;
use App\Traits\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\Peserta\CreatePesertaRequest;
use App\Http\Requests\User\LoginRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private UserService $userService) {}

    public function login(LoginRequest $request)
    {
        try {
            $data = UserDTO::UserLoginDTO($request->validated('no_telp'));

            $user = User::where('no_telp', $data['no_telp'])->first();
            if (!$user) {
                return $this->error('Pengguna tidak ditemukan', 409);
            }

            $token = JWTAuth::fromUser($user);

            return $this->success([
                'token' => $token,
                'type' => 'bearer',
            ], 'Login berhasil', 200);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->error('Gagal membuat token', 500, null);
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat login', 500, null);
        }
    }

    public function register(CreatePesertaRequest $request)
    {
        $data = UserDTO::UserRegisterDTO(
            $request->validated('nama'),
            $request->validated('jenis_kelamin'),
            $request->validated('no_telp'),
            $request->validated('jenjang_sekolah')
        );

        $result = $this->userService->register($data);

        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }

        return $this->success($data, $result['message'], 201);
    }

    public function refresh()
    {
        try {
            $token = JWTAuth::refresh();
            return $this->success([
                'token' => $token,
                'type' => 'bearer'
            ], 'Token refreshed successfully');
        } catch (\Exception $e) {
            return $this->error('Error refreshing token', 400, null);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->success(null, 'Logout successful', 204);
        } catch (\Exception $e) {
            return $this->error('Error logging out', 400, null);
        }
    }

    public function me()
    {
        $user = auth()->user();
        $userData = [
            'id' => $user->id,
            'no_telp' => $user->no_telp,
        ];
        if ($userData['success'] == false) {
            return $this->error($userData['message'], 400, null);
        }

        return $this->success($userData, $userData['message'], 200);
    }
}
