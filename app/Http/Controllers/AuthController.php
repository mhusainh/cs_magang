<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\UserResource;
use App\Models\PesertaPpdb as Peserta;
use App\Http\Resources\RegisterResource;
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

    public function register(Request $request)
    {
        try {
            $credentials = $request->validate([
                'nama' => 'required|string|max:255',
                'no_telp' => 'required|string|max:15',
                'jenis_kelamin' => 'required|string',
                'jenjang_sekolah' => 'required|string',
            ]);

            $existingUser = User::where('no_telp', $credentials['no_telp'])->first();
            if ($existingUser) {
                return $this->error('Nomor telepon sudah digunakan', 422);
           }
            $user = User::create([
                'no_telp' => $credentials['no_telp']
            ]);
            $peserta = Peserta::create([
                'user_id' => $user->id,
                'nama' => $credentials['nama'],
                'no_telp' => $credentials['no_telp'],
                'jenis_kelamin' => $credentials['jenis_kelamin'],
                'jenjang_sekolah' => $credentials['jenjang_sekolah'],
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
