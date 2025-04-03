<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PesertaController;

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
});

// Protected routes (memerlukan login)
Route::middleware('auth:api')->group(function () {
    // Routes yang bisa diakses user dan admin
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Routes khusus user
    Route::middleware('role:user')->group(function () {
        Route::get('/peserta/user/{userId}', [PesertaController::class, 'getByUserId']);
        Route::post('/peserta', [PesertaController::class, 'create']);
        Route::put('/peserta', [PesertaController::class, 'updateByUser']); //coba
        Route::put('/peserta/form-peserta', [PesertaController::class, 'inputFormPeserta']); //coba
    });

    // Routes khusus admin
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::get('/users', [UserController::class, 'getAll']);
        Route::get('/users/{id}', [UserController::class, 'getById']);
        Route::put('/users/{id}', [UserController::class, 'update']);
        Route::delete('/users/{id}', [UserController::class, 'delete']);

        // Peserta management
        Route::get('/peserta', [PesertaController::class, 'getAll']);
        Route::get('/peserta/{id}', [PesertaController::class, 'getById']);
        Route::delete('/peserta/{id}', [PesertaController::class, 'delete']);
    });
});
