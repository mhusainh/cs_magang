<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DTO\UserDTO;
use App\Traits\ApiResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\PesertaPpdb as Peserta;
use App\Http\Requests\Peserta\CreatePesertaRequest;
use App\Http\Requests\User\LoginRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request)
    {
        try {
            $data = UserDTO::UserLoginDTO($request->validated('no_telp'));

            $user = User::where('no_telp', $data['no_telp'])->first();
            if (!$user) {
                return $this->error('Pengguna tidak ditemukan', 404);
            }

            $token = JWTAuth::fromUser($user);

            return $this->success([
                'token' => $token,
                'type' => 'bearer',
            ], 'Login berhasil');
        } catch (ValidationException $e) {
            return $this->error('Data tidak valid', 422, $e->errors());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $this->error('Gagal membuat token', 500, null);
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat login', 500, null);
        }
    }

    public function register(CreatePesertaRequest $request)
    {
        try {
            $data = UserDTO::UserRegisterDTO(
                $request->validated('nama'),
                $request->validated('jenis_kelamin'),
                $request->validated('no_telp'),
                $request->validated('jenjang_sekolah')
            );

            $existingUser = User::where('no_telp', $data['no_telp'])->first();
            if ($existingUser) {
                return $this->error('Nomor telepon sudah digunakan', 422, null);
            }

            $user = User::create([
                'no_telp' => $data['no_telp']
            ]);

            Peserta::create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'no_telp' => $data['no_telp'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'jenjang_sekolah' => $data['jenjang_sekolah'],
            ]);

            // dd($peserta);
            return $this->success($data, 'Pendaftaran berhasil', 201);
        } catch (ValidationException $e) {
            return $this->error('Data tidak valid', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat pendaftaran', 500, null);
        }
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
            return $this->error('Error refreshing token', 401, null);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->success(null, 'Logout successful', 204);
        } catch (\Exception $e) {
            return $this->error('Error logging out', 401, null);
        }
    }

    public function me()
    {
        $user = auth()->user();
        $userData = [
            'id' => $user->id,
            'no_telp' => $user->no_telp,
        ];
        return $this->success($userData, 'User retrieved successfully', 200);
    }
}