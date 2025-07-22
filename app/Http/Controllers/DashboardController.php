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
        $data = [
            'total_surat_masuk' => 0, // Tambahkan query sesuai model
            'total_surat_keluar' => 0,
            'total_draft' => 0,
            'total_disposisi' => 0,
        ];
        return view('dashboard.user', $data);
    }
} 