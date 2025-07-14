<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BuatSuratController;
use App\Http\Controllers\EntriSuratController;
use App\Http\Controllers\KotakMasukController;
use App\Http\Controllers\ReportSuratController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('post.login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('entrisurat/post/file/scan/{entri_surat_id}', [EntriSuratController::class, "scanfile"])->name('entrisurat.post.file.scan');
Route::resource('entrisurat', EntriSuratController::class);


Route::resource('buatsurat', BuatSuratController::class);

Route::prefix('report')->name('report.')->group(function () {  
    Route::get('surat', [ReportSuratController::class, "surat"])->name('surat');
    Route::get('statistik', [ReportSuratController::class, "statistik"])->name('statistik');
});

Route::get('kotakmasuk/disposisi/{id}', [KotakMasukController::class, "disposisi"])->name('kotakmasuk.disposisi');
Route::post('kotakmasuk/disposisi', [KotakMasukController::class, "storeDisposisi"])->name('kotakmasuk.post.disposisi');
Route::resource('kotakmasuk', KotakMasukController::class); 
