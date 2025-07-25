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
use App\Models\DisposisiBaru;

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

        $userId = Auth::user()->id;

        $total_entry_surat = EntrySuratIsi::where('created_by', $userId)->count();

        $total_disposisi = \App\Models\DisposisiBaru::with('tindakans', 'entrysurat')
            ->where(function ($query) use ($userId) {
                $query->whereRaw("FIND_IN_SET(?, kepada)", [$userId]);
            })
            ->latest()
            ->count();



        // dd($userId);

        // Total surat masuk
        // $total_surat_masuk = EntrySuratIsi::whereHas('tujuanSurat', function ($q) use ($userId) {
        //     $q->where('userid_tujuan', $userId);
        // })->count();

        // // Total surat keluar
        // $total_surat_keluar = SuratKeluarIsi::where('user_id_pembuat', $userId)->count();

        // // Total draft (misal status = 1 adalah draft)
        // $total_draft = SuratKeluarIsi::where('user_id_pembuat', $userId)
        //     ->where('status', 1)
        //     ->count();

        // Total disposisi

        // Surat masuk terbaru
        // $latest_surat_masuk = EntrySuratIsi::whereHas('tujuanSurat', function ($q) use ($userId) {
        //     $q->where('userid_tujuan', $userId);
        // })->orderBy('created_at', 'desc')->take(5)->get();

        // // Surat keluar terbaru
        // $latest_surat_keluar = SuratKeluarIsi::where('user_id_pembuat', $userId)
        //     ->orderBy('created_at', 'desc')->take(5)->get();

        // // Draft terbaru (status = 1)
        // $latest_draft = SuratKeluarIsi::where('user_id_pembuat', $userId)
        //     ->where('status', 1)
        //     ->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.user', [
            // 'total_surat_masuk' => $total_surat_masuk,
            // 'total_surat_keluar' => $total_surat_keluar,
            // 'total_draft' => $total_draft,
            'total_disposisi' => $total_disposisi,
            'total_entry_surat' => $total_entry_surat,
            // 'latest_surat_masuk' => $latest_surat_masuk,
            // 'latest_surat_keluar' => $latest_surat_keluar,
            // 'latest_draft' => $latest_draft
        ]);
    }
}
