<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Services\UserService;
use App\Services\PesanService;
use App\Services\TagihanService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\Peserta\CreatePesertaRequest;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserService $userService,
        private PesanService $pesanService,
        private TagihanService $tagihanService
    ) {}

    public function login(LoginRequest $request)
    {
        try {
            $data = UserDTO::UserLoginDTO($request->validated('no_telp'));

            $user = User::where('no_telp', $data['no_telp'])->first();
            if (!$user) {
                return $this->error('Pengguna tidak ditemukan', 409);
            }
            if ($user->status !== 1) {
                return $this->error('Harap Membayar biaya pendaftaran akun', 401, $user->tagihan->qr_data);
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
        $dataTagihan = [
            'user_id' => $result['data']->id,
            'nama_tagihan' => 'Registrasi',
            'total' => 1,
        ];
        
        $tagihan = $this->tagihanService->create($dataTagihan);
        if (!$tagihan['success']) {
            return $this->error($tagihan['message'], 400, null);
        }

        $dataPesan = [
            'user_id' => $result['data']->id,
            'judul' => 'Registrasi Berhasil',
            'deskripsi' => "Selamat! Pendaftaran atas nama {$data['nama']} berhasil dilakukan",
        ];

        $pesan = $this->pesanService->create($dataPesan);
        if (!$pesan['success']) {
            return $this->error($pesan['message'], 400, null);
        }

        return $this->success($tagihan['data'], $result['message'], 201);
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
            return $this->success(null, 'Logout successful', 200);
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
        return $this->success($userData, 'Data user berhasil diambil', 200);
    }
}
