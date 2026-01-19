<?php

use App\Http\Controllers\Api\PengaduanController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/register', [UserController::class, 'register'])->name('register');

//role admin
Route::middleware(['auth:sanctum', 'CekRole:admin'])->group(function () {
    Route::patch('/pengaduan/admin/{id}', [PengaduanController::class, 'update']);
    Route::get('/pengaduan/admin', [PengaduanController::class, 'showAll']);
    
    // Tugas Saya: Admin hapus pengaduan
    Route::delete('/admin/pengaduan/{id}', [PengaduanController::class, 'destroy']);
});

//untuk tampil data pengaduan (Umum)
Route::get('/pengaduan', [PengaduanController::class, 'dataPengaduan']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/get', [PengaduanController::class, 'stores']);
    Route::post('/pengaduan', [PengaduanController::class, 'store']);
    Route::get('/logout', [UserController::class, 'logout']);

    // --- BAGIAN TUGAS SAYA ---
    // Menampilkan pengaduan milik user login
    Route::get('/user/pengaduan/me', [PengaduanController::class, 'pengaduanMe']);
    // Menghapus pengaduan milik sendiri
    Route::delete('/user/pengaduan/{id}', [PengaduanController::class, 'destroyMe']);
    // Update Profile
    Route::patch('/user/profile', [UserController::class, 'updateProfile']);
    // Menampilkan detail pengaduan berdasarkan ID
    Route::get('/pengaduan/{id}', [PengaduanController::class, 'show']);
});