<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluarIsi;
use Illuminate\Http\Request;

class SuratTerkirimController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;
        $suratTerkirim = SuratKeluarIsi::query();
        if ($q) {
            $suratTerkirim->where(function ($query) use ($q) {
                $query->where('nosurat', 'like', "%$q%")
                    ->orWhere('hal', 'like', "%$q%")
                    ->orWhere('kodeklasifikasi', 'like', "%$q%")
                    ->orWhere('kepada', 'like', "%$q%")
                    ->orWhere('ttd_nama', 'like', "%$q%")
                    ->orWhere('tembusan', 'like', "%$q%")
                    ->orWhere('sifat', 'like', "%$q%")
                    ->orWhere('status', 'like', "%$q%")
                    ;
            });
        }
        $suratTerkirim = $suratTerkirim->orderBy('created_at', 'desc')->paginate(10);
        return view('suratterkirim.index', compact('suratTerkirim'));
    }

    public function show($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        return view('suratterkirim.show', compact('suratTerkirim'));
    }

    public function destroy($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        $suratTerkirim->delete();
        return redirect()->route('suratterkirim.index')->with('success', 'Surat Terkirim berhasil dihapus.');
    }

    public function cetak($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        return view('suratterkirim.cetak', compact('suratTerkirim'));
    }
}

