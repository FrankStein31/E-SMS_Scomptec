<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BuatSuratController;
use App\Http\Controllers\EntriSuratController;
use App\Http\Controllers\KotakMasukController;
use App\Http\Controllers\ReportSuratController;
use App\Http\Controllers\DraftSuratController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

Route::resource('draft-surat', DraftSuratController::class);
Route::get('draft-surat/create', [DraftSuratController::class, 'create'])->name('draft_surat.create');
Route::post('draft-surat', [DraftSuratController::class, 'store'])->name('draft-surat.store');
Route::get('draft-surat/{id}', [DraftSuratController::class, 'show'])->name('draft_surat.show');
Route::get('draft-surat/{id}/edit', [DraftSuratController::class, 'edit'])->name('draft_surat.edit');
Route::put('draft-surat/{id}', [DraftSuratController::class, 'update'])->name('draft_surat.update');
Route::delete('draft-surat/{id}', [DraftSuratController::class, 'destroy'])->name('draft_surat.destroy');
Route::get('/draft-surat', [DraftSuratController::class, 'index'])->name('draftsurat.index');
