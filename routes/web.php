<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BuatSuratController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntriSuratController;
use App\Http\Controllers\KotakMasukController;
use App\Http\Controllers\ReportSuratController;
use App\Http\Controllers\DraftSuratController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratTerkirimController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MasterKlasifikasiController;
use App\Http\Controllers\DaftarAlamatController;
use App\Http\Controllers\TindakanDisposisiController;
use App\Http\Controllers\UnitKerjaController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('post.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::post('entrisurat/post/file/scan/{entri_surat_id}', [EntriSuratController::class, "scanfile"])->name('entrisurat.post.file.scan');
Route::resource('entrisurat', EntriSuratController::class);


Route::resource('buatsurat', BuatSuratController::class);

Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
Route::get('user/search', [UserController::class, 'search'])->name('user.search');
Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
Route::resource('user', UserController::class);

Route::resource('unitkerja', UnitKerjaController::class);

Route::prefix('report')->name('report.')->group(function () {
    Route::get('surat', [ReportSuratController::class, "surat"])->name('surat');
    Route::get('statistik', [ReportSuratController::class, "statistik"])->name('statistik');
});

Route::resource('klasifikasi', MasterKlasifikasiController::class);

Route::resource('daftar-alamat', DaftarAlamatController::class);

Route::resource('tindakan-disposisi', TindakanDisposisiController::class);

Route::get('kotakmasuk/disposisi/{id}', [KotakMasukController::class, "disposisi"])->name('kotakmasuk.disposisi');
Route::post('kotakmasuk/disposisi', [KotakMasukController::class, "storeDisposisi"])->name('kotakmasuk.post.disposisi');
Route::resource('kotakmasuk', KotakMasukController::class);

Route::resource('draft-surat', DraftSuratController::class);
Route::get('draft-surat/create', [DraftSuratController::class, 'create'])->name('draft_surat.create');
Route::post('draft-surat', [DraftSuratController::class, 'store'])->name('draft-surat.store');
Route::get('/draft-surat', [DraftSuratController::class, 'index'])->name('draft_surat.index');

Route::get('draft-surat/{id}', [DraftSuratController::class, 'show'])->name('draft_surat.show');
Route::get('draft-surat/{id}/edit', [DraftSuratController::class, 'edit'])->name('draft_surat.edit');
Route::put('draft-surat/{id}', [DraftSuratController::class, 'update'])->name('draft_surat.update');
Route::delete('draft-surat/{id}', [DraftSuratController::class, 'destroy'])->name('draft_surat.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});


Route::resource('/surat-keluar', SuratKeluarController::class);
Route::get('/suratkeluar', [SuratKeluarController::class, 'index'])->name('suratkeluar.index');
Route::get('/suratkeluar/create', [SuratKeluarController::class, 'create'])->name('suratkeluar.create');
Route::post('/suratkeluar', [SuratKeluarController::class, 'store'])->name('suratkeluar.store');
Route::get('/suratkeluar/cetak', [SuratKeluarController::class, 'cetak'])->name('suratkeluar.cetak');




Route::resource('suratterkirim', SuratTerkirimController::class);
Route::get('/suratterkirim', [SuratTerkirimController::class, 'index'])->name('suratterkirim.index');
Route::get('/surat-terkirim/{id}', [SuratTerkirimController::class, 'show'])->name('suratterkirim.show');
Route::get('/surat-terkirim/{id}/cetak', [SuratTerkirimController::class, 'cetak'])->name('suratterkirim.cetak');
Route::get('suratterkirim/{id}/cetak', [SuratTerkirimController::class, 'cetak'])->name('suratterkirim.cetak');
Route::delete('/surat-terkirim/{id}', [SuratTerkirimController::class, 'destroy'])->name('suratterkirim.destroy');
Route::get('/surat-terkirim/data', [SuratTerkirimController::class, 'getData'])->name('suratterkirim.getdata');



Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
Route::get('/disposisi/{id}', [DisposisiController::class, 'show'])->name('disposisi.show');
