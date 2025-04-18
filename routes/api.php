<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PekerjaanOrtuController;
use App\Http\Controllers\PengajuanBiayaController;
use App\Http\Controllers\KetentuanBerkasController;
use App\Http\Controllers\BiayaPendaftaranController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// Public routes (bisa diakses tanpa login)
Route::get('home', [ImageController::class, 'getAllHomepage']);
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:api')->post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->post('logout', [AuthController::class, 'logout']);
});

// Protected routes (memerlukan login)
Route::middleware('auth:api')->group(function () {
    // Routes yang bisa diakses user dan admin
    Route::get('jurusan', [JurusanController::class, 'getByJenjang']);
    Route::get('pekerjaan-ortu', [PekerjaanOrtuController::class, 'getAll']);

    // Routes khusus user
    Route::middleware('role:user')->group(function () {
        route::prefix('user')->group(function () {
            // Home
            Route::get('home', [HomeController::class, 'index']);

            // Berita
            Route::get('berita', [ImageController::class, 'getBeritaByUser']);

            // Berkas Management
            Route::get('berkas', [KetentuanBerkasController::class, 'getKetentuanBerkas']);
            Route::post('berkas/upload', [BerkasController::class, 'uploadBerkas']); // Menerima array files dan ketentuan_berkas_ids dalam request body
            Route::put('berkas/{id}', [BerkasController::class, 'updateBerkas']);

            // COBA
            // Media ({$nama} = jadwal || pengajuan_biaya)
            Route::get('media/{nama}', [ImageController::class, 'GetByUser']);

            // User profile
            Route::get('profile', [AuthController::class, 'me']);
            Route::put('profile', [UserController::class, 'updateProfile']);

            // Peserta Management
            Route::get('peserta', [PesertaController::class, 'getByUser']); // Mengambil data peserta dan berkas berdasarkan user_id
            Route::put('peserta', [PesertaController::class, 'updateByUser']);
            Route::put('peserta/form-peserta', [PesertaController::class, 'inputFormPeserta']);

            // COBA
            // Pengajuan Biaya Management
            Route::get('pengajuan-biaya', [PengajuanBiayaController::class, 'getAll']);

            // COBA
            // Pesan Management
            Route::get('pesan', [PesanController::class, 'getByUser']);
        });
    });

    // Routes khusus admin
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::prefix('admin')->group(function () {

            Route::get('/profile', [AuthController::class, 'me']);

            // User management
            Route::get('/users', [UserController::class, 'getAll']);
            Route::get('/user/{id}', [UserController::class, 'getById']);
            Route::put('/user', [UserController::class, 'update']);
            Route::delete('/user/{id}', [UserController::class, 'delete']);

            // Peserta management
            Route::get('/pesertas', [PesertaController::class, 'getAll']);
            Route::get('peserta/{id}', [PesertaController::class, 'getById']);
            Route::delete('/peserta/{id}', [PesertaController::class, 'delete']);
            Route::get('/peserta/user/{userId}', [PesertaController::class, 'getByUserId']);
            Route::post('/peserta', [PesertaController::class, 'create']);

            // Tagihan Management
            Route::post('tagihan', [TagihanController::class, 'create']);
            Route::put('tagihan/{id}', [TagihanController::class, 'update']);
            Route::delete('tagihan/{id}', [TagihanController::class, 'delete']);

            // Transaksi Management
            Route::delete('transaksi/{id}', [TransaksiController::class, 'delete']);
            Route::put('transaksi/{id}', [TransaksiController::class, 'update']);

            // Jurusan Management            
            Route::post('jurusan', [JurusanController::class, 'create']);
            Route::put('jurusan/{id}', [JurusanController::class, 'update']);
            Route::delete('jurusan/{id}', [JurusanController::class, 'delete']);
            Route::get('jurusan', [JurusanController::class, 'getAll']);
            Route::get('jurusan/{id}', [JurusanController::class, 'getById']);

            // Pekerjaan Ortu Management
            Route::post('pekerjaan-ortu', [PekerjaanOrtuController::class, 'create']);
            Route::put('pekerjaan-ortu/{id}', [PekerjaanOrtuController::class, 'update']);
            Route::delete('pekerjaan-ortu/{id}', [PekerjaanOrtuController::class, 'delete']);
            Route::get('pekerjaan-ortu/{id}', [PekerjaanOrtuController::class, 'getById']);

            // Tagihan Management
            Route::get('tagihan', [TagihanController::class, 'getAll']);
            Route::get('tagihan/{id}', [TagihanController::class, 'getById']);

            // Transaksi Management
            Route::get('transaksi', [TransaksiController::class, 'getAll']);
            Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
            Route::post('transaksi', [TransaksiController::class, 'create']);

            // Homepage Management
            Route::post('homepage', [ImageController::class, 'uploadHomepage']);
            Route::get('homepage', [ImageController::class, 'getAllHomepage']);
            Route::get('homepage/{id}', [ImageController::class, 'getHomepageById']);
            Route::put('homepage/{id}', [ImageController::class, 'updateHomepage']);
            Route::delete('homepage/{id}', [ImageController::class, 'deleteHomepage']);

            // Berita Management
            Route::post('berita', [ImageController::class, 'uploadBerita']);
            Route::get('berita', [ImageController::class, 'getAllBerita']);
            Route::get('berita/{id}', [ImageController::class, 'getBeritaById']);
            Route::put('berita/{id}', [ImageController::class, 'updateBerita']);
            Route::delete('berita/{id}', [ImageController::class, 'deleteBerita']);

            // Ketentuan Berkas Management
            Route::get('ketentuan-berkas', [KetentuanBerkasController::class, 'getAll']);
            Route::get('ketentuan-berkas/{id}', [KetentuanBerkasController::class, 'getById']);
            Route::post('ketentuan-berkas', [KetentuanBerkasController::class, 'create']);
            Route::put('ketentuan-berkas/{id}', [KetentuanBerkasController::class, 'update']);
            Route::delete('ketentuan-berkas/{id}', [KetentuanBerkasController::class, 'delete']);

            // Berkas Peserta Management (untuk admin)
            Route::delete('berkas/{id}', [BerkasController::class, 'deleteBerkas']);
            Route::get('berkas/peserta/{pesertaId}', [BerkasController::class, 'getBerkasByPesertaId']);

            //COBA
            // Media Management (jadwal dan pengajuan_biaya)
            Route::get('media', [ImageController::class, 'getAll']);
            Route::get('media/{id}', [ImageController::class, 'getById']);
            Route::post('media/jadwal', [ImageController::class, 'uploadJadwal']);
            Route::post('media/pengajuan-biaya', [ImageController::class, 'uploadPengajuanBiaya']);
            Route::put('media/{id}', [ImageController::class, 'update']);
            Route::delete('media/{id}', [ImageController::class, 'delete']);

            // COBA
            // Pengajuan Biaya Management
            Route::get('pengajuan-biaya', [PengajuanBiayaController::class, 'getAll']);
            Route::get('pengajuan-biaya/{id}', [PengajuanBiayaController::class, 'getById']);
            Route::post('pengajuan-biaya', [PengajuanBiayaController::class, 'create']);
            Route::put('pengajuan-biaya/{id}', [PengajuanBiayaController::class, 'update']);
            Route::delete('pengajuan-biaya/{id}', [PengajuanBiayaController::class, 'delete']);

            // COBA
            // Biaya Pendaftaran Management
            Route::get('biaya-pendaftaran', [BiayaPendaftaranController::class, 'getAll']);
            Route::get('biaya-pendaftaran/{id}', [BiayaPendaftaranController::class, 'getById']);
            Route::post('biaya-pendaftaran', [BiayaPendaftaranController::class, 'create']);
            Route::put('biaya-pendaftaran/{id}', [BiayaPendaftaranController::class, 'update']);
            Route::delete('biaya-pendaftaran/{id}', [BiayaPendaftaranController::class, 'delete']);
            
            // COBA
            // Pesan Management
            Route::get('pesan', [PesanController::class, 'getAll']);
            Route::get('pesan/{id}', [PesanController::class, 'getById']);
            Route::get('pesan/user/{userId}', [PesanController::class, 'getByUserId']);
            Route::post('pesan', [PesanController::class, 'create']);
            Route::put('pesan/{id}', [PesanController::class, 'update']);
            Route::delete('pesan/{id}', [PesanController::class, 'delete']);
        });
    });
});
