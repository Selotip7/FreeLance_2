<?php

use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');


//role admin
Route::middleware(['auth:sanctum', 'CekRole:admin'])->group(function () {

    Route::get('/pengaduan/admin', [PengaduanController::class, 'showAll']);
});

//untuk tampil data pengaduan
Route::get('/pengaduan', [PengaduanController::class, 'dataPengaduan']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/logoutAll', [UserController::class, 'logoutAll']);
    });




// Route::middleware(['auth:sanctum', 'CheckRole:masyarakat'])->group(function () {
//     Route::get('/pengaduan', [PengaduanController::class, 'index']);
//     Route::post('/pengaduan', [PengaduanController::class, 'store']);
// });
