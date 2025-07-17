<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluar;

class SuratKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data sementara, tanpa database
        $suratKeluar = [
            [
                'no_surat' => '001/SMK/2025',
                'sifat' => 'Penting',
                'jenis' => 'Resmi',
                'hal' => 'Permohonan Izin',
                'tgl_surat' => '2025-07-15',
                'klasifikasi' => 'Rahasia',
                'kepada' => 'Dinas Pendidikan',
                'nama_final' => 'Bayu Gilang',
                'jabatan_final' => 'Kepala Sekolah',
                'satker_final' => 'SMKN 1 Kota',
            ],
            [
                'no_surat' => '002/SMK/2025',
                'sifat' => 'Biasa',
                'jenis' => 'Pengumuman',
                'hal' => 'Jadwal Ujian',
                'tgl_surat' => '2025-07-14',
                'klasifikasi' => 'Umum',
                'kepada' => 'Seluruh Siswa',
                'nama_final' => 'Admin TU',
                'jabatan_final' => 'Tata Usaha',
                'satker_final' => 'SMKN 1 Kota',
            ]
        ];

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
}
