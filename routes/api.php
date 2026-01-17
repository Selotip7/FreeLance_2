<?php
use App\Http\Controllers\Api\PengaduanController;

// Route::get('pengaduan', [PengaduanController::class, 'index']);
Route::get('pengaduan', [PengaduanController::class, 'totalPengaduan']);// semua pengaduan
Route::get('pengaduan/{id}', [PengaduanController::class, 'show']); // detail pengaduan
Route::get('my-pengaduan', [PengaduanController::class, 'myReports']); // pengaduan user login
Route::get('pengaduan/status/{status}', [PengaduanController::class, 'status']); // filter status

?>