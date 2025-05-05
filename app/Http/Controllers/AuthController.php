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
use App\Services\BiayaPendaftaranService;
use App\Http\Requests\Auth\LoginAdminRequest;
use App\Repositories\BiayaPendaftaranRepository;
use App\Http\Requests\Peserta\CreatePesertaRequest;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(
        private UserService $userService,
        private PesanService $pesanService,
        private TagihanService $tagihanService,
        private BiayaPendaftaranService $biayaPendaftaranService
    ) {}

    public function loginAdmin(LoginAdminRequest $request)
    {
        try {
            $credentials = UserDTO::UserLoginDTO(
                $request->validated('no_telp'),
                $request->validated('password')
            );

            if (! $token = auth()->attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

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


    public function login(LoginRequest $request)
    {
        try {
            $data = UserDTO::UserLoginDTO($request->validated('no_telp'));

            $user = User::where('no_telp', $data['no_telp'])->first();
            if (!$user) {
                return $this->error('Pengguna tidak ditemukan', 200);
            }
            if ($user->status !== 1) {
                $qr_data = $user->tagihan()
                    ->where('nama_tagihan', 'Registrasi')
                    ->first()
                    ?->qr_data;
                return $this->error('Harap Membayar biaya pendaftaran akun', 200, $qr_data ? ['qr_data' => $qr_data] : null);
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
        $biayaPendaftaran = $this->biayaPendaftaranService->getOnTop();
        if (!$biayaPendaftaran['success']) {
            return $this->error($biayaPendaftaran['message'], 400, null);
        }
        $result = $this->userService->register($data);
        if (!$result['success']) {
            return $this->error($result['message'], 400, null);
        }

        $dataTagihan = [
            'user_id' => $result['data']->id,
            'nama_tagihan' => 'Registrasi',
            'total' => $biayaPendaftaran['data']->nominal,
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
            $token = JWTAuth::parseToken()->refresh();
            return $this->success([
                'token' => $token,
                'type' => 'bearer'
            ], 'Token berhasil diperbarui', 200);
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat memperbarui token', 400, null);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return $this->success(null, 'Logout berhasil', 200);
        } catch (\Exception $e) {
            return $this->error('Terjadi kesalahan saat logout', 400, null);
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
