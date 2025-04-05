<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\TagihanController;
use App\Http\Controllers\TransaksiController;
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
Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:api')->post('refresh', [AuthController::class, 'refresh']);
});

// Protected routes (memerlukan login)
Route::middleware('auth:api')->group(function () {
    // Routes yang bisa diakses user dan admin
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Routes khusus user
    Route::middleware('role:user')->group(function () {
        route::prefix('user')->group(function () {
            // User profile
            Route::get('/profile', [AuthController::class, 'me']);
            Route::put('/profile', [UserController::class, 'updateProfile']);

            // Peserta Management
            Route::get('peserta', [PesertaController::class, 'getByUser']);
            Route::put('peserta', [PesertaController::class, 'updateByUser']);
            Route::put('peserta/form-peserta', [PesertaController::class, 'inputFormPeserta']);

            // Tagihan Management
            Route::get('tagihan', [TagihanController::class, 'getAll']);
            Route::get('tagihan/{id}', [TagihanController::class, 'getById']);

            // Transaksi Management
            Route::get('transaksi', [TransaksiController::class, 'getAll']);
            Route::get('transaksi/{id}', [TransaksiController::class, 'getById']);
            Route::post('transaksi', [TransaksiController::class, 'create']);
            
        });
    });
    
    // Routes khusus admin
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::prefix('admin')->group(function () {
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
            Route::delete('transaksi/{id}', [TransaksiController::class,'delete']);
            Route::put('transaksi/{id}', [TransaksiController::class,'update']);

        });
    });
});
