<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\DTO\UserDTO;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\UserResource;
use App\Models\PesertaPpdb as Peserta;
use App\Http\Resources\RegisterResource;
use App\Http\Requests\Peserta\CreatePesertaRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'no_telp' => 'required|string|max:15',
        ]);

        $user = User::where('no_telp', $credentials['no_telp'])->first();
        if (!$user) {
            return $this->error('Pengguna tidak ditemukan', 404);
        }

        $token = JWTAuth::fromUser($user);

        return $this->success([
            'token' => $token,
            'type' => 'bearer',
        ], 'Login berhasil');
    }

    public function register(CreatePesertaRequest $request)
    {
        try {
            $data = UserDTO::UserRegisterRequest(
                $request->validated('nama'),
                $request->validated('jenis_kelamin'),
                $request->validated('no_telp'), 
                $request->validated('jenjang_sekolah')
            );

            $existingUser = User::where('no_telp', $data['no_telp'])->first();
            if ($existingUser) {
                return $this->error('Nomor telepon sudah digunakan', 422);
            }

            $user = User::create([
                'no_telp' => $data['no_telp']
            ]);

            $peserta = Peserta::create([
                'user_id' => $user->id,
                'nama' => $data['nama'],
                'no_telp' => $data['no_telp'],
                'jenis_kelamin' => $data['jenis_kelamin'],
                'jenjang_sekolah' => $data['jenjang_sekolah'],
            ]);

            // dd($peserta);
            return $this->success([
                'peserta' => new RegisterResource($peserta)
            ], 'Pendaftaran berhasil', 201);
        } catch (ValidationException $e) {
            return $this->error('Data tidak valid', 422, $e->errors());
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat pendaftaran', 500);
        }
    }
}
