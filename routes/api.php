<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\PekerjaanOrtuController;
use App\Http\Controllers\ImageController;
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
    Route::middleware('auth:api')->post('logout', [AuthController::class,'logout']);
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
            Route::get('/home', [HomeController::class, 'index']);

            // Berita
            Route::get('/berita', [ImageController::class, 'getBeritaByUser']);

            // User profile
            Route::get('/profile', [AuthController::class, 'me']);
            Route::put('/profile', [UserController::class, 'updateProfile']);

            // Peserta Management
            Route::get('peserta', [PesertaController::class, 'getByUser']);
            Route::put('peserta', [PesertaController::class, 'updateByUser']);
            Route::put('peserta/form-peserta', [PesertaController::class, 'inputFormPeserta']);
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
        });
    });
});
