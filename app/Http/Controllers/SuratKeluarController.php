<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluarIsi;

class SuratKeluarController extends Controller
{
    /**     
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = auth()->user()->username;
        $suratKeluar = SuratKeluarIsi::with([
            'jenis',
            'klasifikasi',
            'pembuat',
            'userFinal.satker'
        ])->where('user_id_pembuat', $userId)->get();

        return view('suratkeluar.index', compact('suratKeluar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('suratkeluar.create');
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
    public function show(string $id)
    {
        //
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

    public function cetak()
    {
        $suratKeluar = SuratKeluarIsi::all();

        return view('suratkeluar.cetak', compact('suratKeluar'));
    }
}
