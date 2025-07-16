<?php

use App\Http\Controllers\EntriSuratController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('landing');
})->name('landing');

// Jika belum ada auth, tambahkan route dummy agar halaman bisa diakses
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::post('entrisurat/post/file/scan/{entri_surat_id}', [EntriSuratController::class, "scanfile"])->name('entrisurat.post.file.scan');
// Route::resource('entrisurat', EntriSuratController::class);
Route::get('/entrisurat/create', [EntriSuratController::class, 'create'])->name('entrisurat.create');
Route::get('/entrisurat', [EntriSuratController::class, 'index'])->name('entrisurat.index');
