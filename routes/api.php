<?php

use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');


//role admin
Route::middleware(['auth:sanctum', 'CekRole:admin'])->group(function () {
    //update status dan catatan pengaduan
    Route::patch('/pengaduan/admin/{id}', [PengaduanController::class, 'update']);
    //menampilkan semua pengaduan
    Route::get('/pengaduan/admin', [PengaduanController::class, 'showAll']);
});

//untuk tampil data pengaduan
Route::get('/pengaduan', [PengaduanController::class, 'dataPengaduan']);


Route::middleware(['auth:sanctum'])->group(function () {
    //Menyimpan pengaduan
    Route::post('/get', [PengaduanController::class, 'stores']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    //logout
    Route::get('/logout', [UserController::class, 'logout']);
    //hapus semua token (tidak dipakai)
    // Route::get('/logoutAll', [UserController::class, 'logoutAll']);
    });




// Route::middleware(['auth:sanctum', 'CheckRole:masyarakat'])->group(function () {
//     Route::get('/pengaduan', [PengaduanController::class, 'index']);
//     Route::post('/pengaduan', [PengaduanController::class, 'store']);
// });
