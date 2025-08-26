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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('post.login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Routes with double submission prevention
Route::middleware('prevent.double.submission')->group(function () {

    // Routes with double submission prevention
    Route::middleware('prevent.double.submission')->group(function () {
        Route::post('entrisurat/post/file/scan/{entri_surat_id}', [EntriSuratController::class, "scanfile"])->name('entrisurat.post.file.scan');
        Route::delete('/entrisurat/scan/{id}/delete', [EntriSuratController::class, 'deleteScan'])->name('entrisurat.scan.delete');
        Route::resource('entrisurat', EntriSuratController::class)->except(['index', 'show']);

        Route::resource('buatsurat', BuatSuratController::class)->except(['index', 'show']);

        Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::resource('user', UserController::class)->except(['index', 'show']);

        Route::resource('unitkerja', UnitKerjaController::class)->except(['index', 'show']);

        Route::resource('klasifikasi', MasterKlasifikasiController::class)->except(['index', 'show']);

        Route::resource('daftar-alamat', DaftarAlamatController::class)->except(['index', 'show']);

        Route::resource('tindakan-disposisi', TindakanDisposisiController::class)->except(['index', 'show']);

        // Route::post('kotakmasuk/disposisi', [KotakMasukController::class, "storeDisposisi"])->name('kotakmasuk.post.disposisi');
        // Route::post('kotakmasuk/disposisi', [KotakMasukController::class, "storeDisposisi"])->name('kotakmasuk.kirim.disposisi');
        Route::resource('kotakmasuk', KotakMasukController::class)->except(['index', 'show']);

        Route::post('kotakmasuk/tandai-dibaca/{entrysurat_id}/{tujuan_id}', [KotakMasukController::class, 'tandaiDibaca'])->name('kotakmasuk.tandai-dibaca');

        Route::post('draft-surat', [DraftSuratController::class, 'store'])->name('draft-surat.store');
        Route::put('draft-surat/{id}', [DraftSuratController::class, 'update'])->name('draft_surat.update');
        Route::delete('draft-surat/{id}', [DraftSuratController::class, 'destroy'])->name('draft_surat.destroy');

        Route::middleware('auth')->group(function () {
            Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        });

        Route::post('/suratkeluar', [SuratKeluarController::class, 'store'])->name('suratkeluar.store');
        Route::resource('/surat-keluar', SuratKeluarController::class)->except(['index', 'show']);

        Route::delete('/surat-terkirim/{id}', [SuratTerkirimController::class, 'destroy'])->name('suratterkirim.destroy');
        Route::resource('suratterkirim', SuratTerkirimController::class)->except(['index', 'show']);

        Route::resource('disposisi', DisposisiController::class)->except(['index', 'show']);

        Route::get('/report/cetak', [ReportSuratController::class, 'cetak'])->name('report.cetak');
    });

    // Routes without double submission prevention (read-only operations)
    Route::get('entrisurat/{id}/export-word', [EntriSuratController::class, 'exportTandaTerimaWord'])->name('entrisurat.exportWord');
    Route::get('entrisurat/{id}/export-surat-word', [EntriSuratController::class, 'exportSuratWord'])->name('entrisurat.exportSuratWord');
    Route::get('entrisurat/{id}/export-surat-dis-word', [EntriSuratController::class, 'exportSuratDisWord'])->name('entrisurat.exportSuratDisWord');
    Route::resource('entrisurat', EntriSuratController::class)->only(['index', 'show']);

    Route::resource('buatsurat', BuatSuratController::class)->only(['index', 'show']);

    Route::get('user/search', [UserController::class, 'search'])->name('user.search');
    Route::resource('user', UserController::class)->only(['index', 'show']);

    Route::get('unitkerja/detail/{id}', [UnitKerjaController::class, 'getDetailData'])->name('unitkerja.detail');
    Route::resource('unitkerja', UnitKerjaController::class)->only(['index', 'show']);

    Route::resource('klasifikasi', MasterKlasifikasiController::class)->only(['index', 'show']);

    Route::resource('daftar-alamat', DaftarAlamatController::class)->only(['index', 'show']);

    Route::resource('tindakan-disposisi', TindakanDisposisiController::class)->only(['index', 'show']);

    Route::get('kotakmasuk/disposisi/{id}', [KotakMasukController::class, "disposisi"])->name('kotakmasuk.disposisi');
    Route::resource('kotakmasuk', KotakMasukController::class)->only(['index', 'show']);

    Route::resource('draft-surat', DraftSuratController::class)->only(['index', 'show']);
    Route::get('draft-surat/create', [DraftSuratController::class, 'create'])->name('draft_surat.create');
    Route::get('/draft-surat', [DraftSuratController::class, 'index'])->name('draft_surat.index');
Route::get('draft-surat/{id}', [DraftSuratController::class, 'show'])->name('draft_surat.show');
    Route::get('draft-surat/{id}/edit', [DraftSuratController::class, 'edit'])->name('draft_surat.edit');

Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

    Route::get('/suratkeluar', [SuratKeluarController::class, 'index'])->name('suratkeluar.index');
    Route::get('/suratkeluar/create', [SuratKeluarController::class, 'create'])->name('suratkeluar.create');
Route::get('/suratkeluar/cetak', [SuratKeluarController::class, 'cetak'])->name('suratkeluar.cetak');
    Route::resource('/surat-keluar', SuratKeluarController::class)->only(['index', 'show']);

    Route::get('/suratterkirim', [SuratTerkirimController::class, 'index'])->name('suratterkirim.index');
Route::get('/surat-terkirim/{id}', [SuratTerkirimController::class, 'show'])->name('suratterkirim.show');
Route::get('/surat-terkirim/{id}/cetak', [SuratTerkirimController::class, 'cetak'])->name('suratterkirim.cetak');
    Route::get('suratterkirim/{id}/cetak', [SuratTerkirimController::class, 'cetak'])->name('suratterkirim.cetak');
Route::get('/surat-terkirim/data', [SuratTerkirimController::class, 'getData'])->name('suratterkirim.getdata');
    Route::resource('suratterkirim', SuratTerkirimController::class)->only(['index', 'show']);

    Route::get('/disposisi', [DisposisiController::class, 'index'])->name('disposisi.index');
Route::get('/disposisi/{id}', [DisposisiController::class, 'show'])->name('disposisi.show');
Route::get('/disposisi/riwayat/{id}', [App\Http\Controllers\DisposisiController::class, 'riwayatSurat'])->name('disposisi.riwayat');
    Route::resource('disposisi', DisposisiController::class)->only(['index', 'show']);

    Route::get('/report/cetak', [ReportSuratController::class, 'cetak'])->name('report.cetak');
});

// Routes without double submission prevention (read-only operations)
Route::prefix('report')->name('report.')->group(function () {
    Route::get('surat', [ReportSuratController::class, "surat"])->name('surat');
    Route::get('statistik', [ReportSuratController::class, "statistik"])->name('statistik');
    Route::get('laporan/cetak', [ReportSuratController::class, "cetak"])->name('laporan.cetak');
});

Route::get('aktivitas', [ReportSuratController::class, 'aktivitas'])->name('aktivitas');
