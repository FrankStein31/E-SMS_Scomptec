<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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