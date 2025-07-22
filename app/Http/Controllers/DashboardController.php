<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterKlasifikasi;
use App\Models\MasterInstansi;
use App\Models\MasterTindakanDisposisi;
use App\Models\EntrySuratIsi;
use App\Models\SuratKeluarIsi;
use App\Models\DraftSurat;
use App\Models\Disposisi;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->jabatan == 'Administrator') {
            return $this->adminDashboard();
        }
        return $this->userDashboard();
    }

    private function adminDashboard()
    {
        $data = [
            'total_users' => User::count(),
            'total_admin' => User::where('jabatan', 'Administrator')->count(),
            'total_user' => User::where('jabatan', '!=', 'Administrator')->count(),
            'total_klasifikasi' => MasterKlasifikasi::count(),
            'total_instansi' => MasterInstansi::count(),
            'total_tindakan' => MasterTindakanDisposisi::count(),
            'total_surat_masuk' => EntrySuratIsi::count(),
            'total_surat_keluar' => SuratKeluarIsi::count(),
            'total_surat_terkirim' => SuratKeluarIsi::where('status', '>=', 2)->count(),
            'latest_users' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'latest_surat_keluar' => SuratKeluarIsi::orderBy('created_at', 'desc')->take(5)->get(),
        ];
        return view('dashboard.admin', $data);
    }

    private function userDashboard()
    {
        $userId = Auth::id();
        $total_surat_masuk = EntrySuratIsi::whereHas('tujuanSurat', function($q) use ($userId) {
            $q->where('userid_tujuan', $userId);
        })->count();
        $total_surat_keluar = SuratKeluarIsi::where('user_id_pembuat', $userId)->count();
        $total_draft = class_exists('App\\Models\\DraftSurat') ? DraftSurat::where('userid_pembuat', $userId)->count() : 0;
        $total_disposisi = class_exists('App\\Models\\Disposisi') ? Disposisi::where('userid_pembuat', $userId)->count() : 0;

        $latest_surat_masuk = EntrySuratIsi::whereHas('tujuanSurat', function($q) use ($userId) {
            $q->where('userid_tujuan', $userId);
        })->orderBy('created_at', 'desc')->take(5)->get();
        $latest_surat_keluar = SuratKeluarIsi::where('user_id_pembuat', $userId)->orderBy('created_at', 'desc')->take(5)->get();

        $data = [
            'total_surat_masuk' => $total_surat_masuk,
            'total_surat_keluar' => $total_surat_keluar,
            'total_draft' => $total_draft,
            'total_disposisi' => $total_disposisi,
            'latest_surat_masuk' => $latest_surat_masuk,
            'latest_surat_keluar' => $latest_surat_keluar,
        ];
        return view('dashboard.user', $data);
    }
} 