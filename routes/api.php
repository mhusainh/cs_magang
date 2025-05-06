<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QrisController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\PesanController;
use App\Http\Controllers\BerkasController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\AngkatanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\BiodataOrtuController;
use App\Http\Controllers\PekerjaanOrtuController;
use App\Http\Controllers\PengajuanBiayaController;
use App\Http\Controllers\KetentuanBerkasController;
use App\Http\Controllers\PenghasilanOrtuController;
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
Route::post('check-status', [QrisController::class, 'checkStatus']);
Route::post('qris/pushNotification', [QrisController::class, 'webhookQris']);
Route::post('va/pushNotification', [QrisController::class, 'webhookvaNumber']);
Route::get('jenjang', [JurusanController::class, 'getUniqueJenjang']);

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('login-admin', [AuthController::class, 'loginAdmin']);
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
            Route::get('progressPayment', [HomeController::class, 'progressPayment']);

            // Berita
            Route::get('berita', [ImageController::class, 'getAllBerita']);

            // Berkas Management
            Route::get('berkas', [KetentuanBerkasController::class, 'getByJenjang']);
            Route::post('berkas/upload', [BerkasController::class, 'uploadBerkas']); // Menerima array files dan ketentuan_berkas_ids dalam request body
            Route::post('berkas/{id}', [BerkasController::class, 'updateBerkas']);

            // Media Management
            Route::get('media/jadwal', [MediaController::class, 'GetJadwalByUser']);
            Route::get('media/pengajuan-biaya', [MediaController::class, 'GetPengajuanBiayaByUser']);

            // User profile
            Route::get('profile', [AuthController::class, 'me']);
            // Route::put('profile', [UserController::class, 'update']);

            // Peserta Management
            Route::get('peserta', [PesertaController::class, 'getByUser']); // Mengambil data peserta dan berkas berdasarkan user_id
            Route::put('peserta', [PesertaController::class, 'updateByUser']);
            Route::put('peserta/form-peserta', [PesertaController::class, 'inputFormPeserta']);
            Route::get('peringkat', [PesertaController::class, 'getPeringkatByUser']);

            // Pengajuan Biaya Management
            Route::get('pengajuan-biaya', [PengajuanBiayaController::class, 'getByUser']);
            Route::put('pengajuan-biaya/wakaf', [PengajuanBiayaController::class, 'wakaf']);
            Route::put('pengajuan-biaya/spp', [PengajuanBiayaController::class, 'spp']);
            Route::put('pengajuan-biaya/book-vee', [PengajuanBiayaController::class, 'bookVee']);
            Route::put('pengajuan-biaya/reguler', [PengajuanBiayaController::class, 'reguler']);
            Route::post('pengajuan-biaya/bayar/wakaf', [PengajuanBiayaController::class, 'createTagihanWakaf']);
            Route::post('pengajuan-biaya/bayar/reguler', [PengajuanBiayaController::class, 'createTagihanPengajuanBiaya']);

            // Biaya Pendaftaran Management
            Route::get('biaya-pendaftaran', [BiayaPendaftaranController::class, 'getOnTop']);

            // Biodata Ortu Management
            Route::post('biodata-ortu', [BiodataOrtuController::class, 'create']);
            Route::put('biodata-ortu', [BiodataOrtuController::class, 'updateByUser']);

            // Pesan Management
            Route::get('pesan', [PesanController::class, 'getByUser']);
            Route::get('pesan/{id}', [PesanController::class, 'getByUserAndId']);

            // Transaksi Management
            Route::get('riwayat', [TransaksiController::class, 'riwayat']);


            // Tagihan Management
            Route::get('tagihan', [TagihanController::class, 'getByUser']);

            // Penghasilan Ortu Management
            Route::get('penghasilan-ortu', [PenghasilanOrtuController::class, 'getAll']);
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
            Route::put('/user/{id}', [UserController::class, 'update']);
            Route::delete('/user/{id}', [UserController::class, 'delete']);
            Route::get('/users/trash', [UserController::class, 'getDeleted']);
            Route::put('/user/{id}/restore', [UserController::class, 'restore']);
            Route::get('/user/progressPayment/{id}', [UserController::class, 'progressPayment']);

            // Peserta management
            Route::get('/pesertas', [PesertaController::class, 'getAll']);
            Route::get('peserta/{id}', [PesertaController::class, 'getById']);
            Route::delete('/peserta/{id}', [PesertaController::class, 'delete']);
            Route::get('/peserta/user/{userId}', [PesertaController::class, 'getByUserId']);
            Route::post('/peserta', [PesertaController::class, 'create']);
            Route::put('/peserta/{id}', [PesertaController::class, 'updateStatus']);
            Route::get('/pesertas/trash', [PesertaController::class, 'getDeleted']);
            Route::put('/peserta/{id}/restore', [PesertaController::class, 'restore']);
            Route::put('peserta/nis/{id}', [PesertaController::class,'updateNis']);
            Route::get('peringkat', [PesertaController::class, 'getPeringkat']);

            // Tagihan Management
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
            Route::get('jurusans/trash', [JurusanController::class, 'getDeleted']);
            Route::put('jurusan/{id}/restore', [JurusanController::class, 'restore']);

            // Pekerjaan Ortu Management
            Route::post('pekerjaan-ortu', [PekerjaanOrtuController::class, 'create']);
            Route::put('pekerjaan-ortu/{id}', [PekerjaanOrtuController::class, 'update']);
            Route::delete('pekerjaan-ortu/{id}', [PekerjaanOrtuController::class, 'delete']);
            Route::get('pekerjaan-ortu/{id}', [PekerjaanOrtuController::class, 'getById']);
            Route::get('pekerjaan-ortu', [PekerjaanOrtuController::class, 'getAll']);
            Route::get('pekerjaan-ortus/trash', [PekerjaanOrtuController::class, 'getDeleted']);
            Route::put('pekerjaan-ortu/{id}/restore', [PekerjaanOrtuController::class, 'restore']);

            // Tagihan Management
            Route::get('tagihan', [TagihanController::class, 'getAll']);
            Route::get('tagihan/{id}', [TagihanController::class, 'getById']);
            Route::delete('tagihan/{id}', [TagihanController::class, 'delete']);
            Route::get('tagihans/trash', [TagihanController::class, 'getDeleted']);
            Route::put('tagihan/{id}/restore', [TagihanController::class, 'restore']);

            // Transaksi Management
            Route::get('transaksi', [TransaksiController::class, 'getAll']);
            Route::get('transaksi/user/{userId}', [TransaksiController::class, 'getByUserId']);
            Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
            Route::post('transaksi', [TransaksiController::class, 'create']);
            Route::get('transaksis/trash', [TransaksiController::class, 'getDeleted']);
            Route::put('transaksi/{id}/restore', [TransaksiController::class, 'restore']);

            // Homepage Management
            Route::post('homepage', [ImageController::class, 'uploadHomepage']);
            Route::get('homepage', [ImageController::class, 'getAllHomepage']);
            Route::get('homepage/{id}', [ImageController::class, 'getHomepageById']);
            Route::post('homepage/{id}', [ImageController::class, 'updateHomepage']);
            Route::delete('homepage/{id}', [ImageController::class, 'deleteHomepage']);

            // Berita Management
            Route::post('berita', [ImageController::class, 'uploadBerita']);
            Route::get('berita', [ImageController::class, 'getAllBerita']);
            Route::get('berita/{id}', [ImageController::class, 'getBeritaById']);
            Route::post('berita/{id}', [ImageController::class, 'updateBerita']);
            Route::delete('berita/{id}', [ImageController::class, 'deleteBerita']);

            // Ketentuan Berkas Management
            Route::get('ketentuan-berkas', [KetentuanBerkasController::class, 'getAll']);
            Route::get('ketentuan-berkas/{id}', [KetentuanBerkasController::class, 'getById']);
            Route::post('ketentuan-berkas', [KetentuanBerkasController::class, 'create']);
            Route::put('ketentuan-berkas/{id}', [KetentuanBerkasController::class, 'update']);
            Route::delete('ketentuan-berkas/{id}', [KetentuanBerkasController::class, 'delete']);
            Route::get('ketentuan-berkass/trash', [KetentuanBerkasController::class, 'getDeleted']);
            Route::put('ketentuan-berkas/{id}/restore', [KetentuanBerkasController::class, 'restore']);

            // Berkas Peserta Management (untuk admin)
            Route::get('berkas', [BerkasController::class, 'getAllBerkas']);
            Route::get('berkas/peserta/{pesertaId}', [BerkasController::class, 'getBerkasByPesertaId']);
            Route::delete('berkas/{id}', [BerkasController::class, 'deleteBerkas']);

            //COBA
            // Media Management (jadwal dan pengajuan_biaya)
            Route::get('media', [MediaController::class, 'getAll']);
            Route::get('media/{id}', [MediaController::class, 'getById']);
            Route::post('media/jadwal', [MediaController::class, 'uploadJadwal']);
            Route::post('media/pengajuan-biaya', [MediaController::class, 'uploadPengajuanBiaya']);
            Route::post('media/{id}', [MediaController::class, 'update']);
            Route::delete('media/{id}', [MediaController::class, 'delete']);

            // Pengajuan Biaya Management
            Route::get('pengajuan-biaya', [PengajuanBiayaController::class, 'getAll']);
            Route::get('pengajuan-biaya/{id}', [PengajuanBiayaController::class, 'getById']);
            Route::post('pengajuan-biaya/reguler', [PengajuanBiayaController::class, 'createReguler']);
            Route::post('pengajuan-biaya/book-vee', [PengajuanBiayaController::class, 'createBookVee']);
            Route::put('pengajuan-biaya/{id}', [PengajuanBiayaController::class, 'update']);
            Route::delete('pengajuan-biaya/{id}', [PengajuanBiayaController::class, 'delete']);

            // Biaya Pendaftaran Management
            Route::get('biaya-pendaftaran', [BiayaPendaftaranController::class, 'getAll']);
            Route::get('biaya-pendaftaran/{id}', [BiayaPendaftaranController::class, 'getById']);
            Route::post('biaya-pendaftaran', [BiayaPendaftaranController::class, 'create']);
            Route::put('biaya-pendaftaran/{id}', [BiayaPendaftaranController::class, 'update']);
            Route::delete('biaya-pendaftaran/{id}', [BiayaPendaftaranController::class, 'delete']);

            // Pesan Management
            Route::get('pesan', [PesanController::class, 'getAll']);
            Route::get('pesan/{id}', [PesanController::class, 'getById']);
            Route::get('pesan/user/{userId}', [PesanController::class, 'getByUserId']);
            Route::post('pesan', [PesanController::class, 'create']);
            Route::put('pesan/{id}', [PesanController::class, 'update']);
            Route::delete('pesan/{id}', [PesanController::class, 'delete']);
            Route::get('pesans/trash', [PesanController::class, 'getDeleted']);
            Route::put('pesan/{id}/restore', [PesanController::class, 'restore']);

            // Biodata Ortu Management
            Route::get('biodata-ortu', [BiodataOrtuController::class, 'getAll']);
            Route::get('biodata-ortu/{id}', [BiodataOrtuController::class, 'getById']);
            Route::put('biodata-ortu/{id}', [BiodataOrtuController::class, 'update']);
            Route::delete('biodata-ortu/{id}', [BiodataOrtuController::class, 'delete']);
            Route::get('biodata-ortus/trash', [BiodataOrtuController::class, 'getDeleted']);
            Route::put('biodata-ortu/{id}/restore', [BiodataOrtuController::class, 'restore']);

            // Penghasilan Ortu Management
            Route::get('penghasilan-ortu', [PenghasilanOrtuController::class, 'getAll']);
            Route::get('penghasilan-ortu/{id}', [PenghasilanOrtuController::class, 'getById']);
            Route::post('penghasilan-ortu', [PenghasilanOrtuController::class, 'create']);
            Route::put('penghasilan-ortu/{id}', [PenghasilanOrtuController::class, 'update']);
            Route::delete('penghasilan-ortu/{id}', [PenghasilanOrtuController::class, 'delete']);
            Route::get('penghasilan-ortus/trash', [PenghasilanOrtuController::class, 'getDeleted']);
            Route::put('penghasilan-ortu/{id}/restore', [PenghasilanOrtuController::class, 'restore']);

            // Angkatan Management
            Route::get('angkatan', [AngkatanController::class, 'getAll']);
            Route::post('angkatan', [AngkatanController::class, 'create']);
            Route::put('angkatan/{id}', [AngkatanController::class, 'update']);
            Route::delete('angkatan/{id}', [AngkatanController::class, 'delete']);
        });
    });
});
