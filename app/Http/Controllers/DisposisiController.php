<?php

namespace App\Http\Controllers;

use App\DataTables\DisposisiBaruDataTable;
use App\Http\Controllers\Controller;
use App\Models\Disposisi;
use App\Models\DisposisiBaru;
use App\Models\EntrySuratIsi;
use App\Models\EntrySuratTujuan;
use App\Models\MasterSatker;
use App\Models\MasterTindakanDisposisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(DisposisiBaruDataTable $dataTable)
    {
        return $dataTable->render('disposisi.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $disposisi = EntrySuratIsi::find($id);
        return view('disposisi.show', compact('disposisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function riwayatSurat($id)
    {
        // Ambil surat masuk
        $surat = \App\Models\EntrySuratIsi::with('createdBy')->findOrFail($id);
        // Ambil semua disposisi baru terkait surat ini
        $riwayat = \App\Models\DisposisiBaru::where('entrysurat_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        // Ambil semua tujuan surat (EntrySuratTujuan)
        $tujuanList = \App\Models\EntrySuratTujuan::where('entrysurat_id', $id)->get();
        return view('disposisi.riwayat', compact('surat', 'riwayat', 'tujuanList'));
    }
}
