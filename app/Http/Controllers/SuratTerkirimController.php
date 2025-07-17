<?php

namespace App\Http\Controllers;

use App\Models\SuratTerkirim;
use Illuminate\Http\Request;

class SuratTerkirimController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $suratTerkirim = [
            (object)[
                'id' => 1,
                'no_surat' => '001',
                'sifat' => 'penting',
                'jenis' => 'undangan',
                'hal' => 'penting',
                'tgl_surat' => '17 Juli 2025',
                'klasifikasi' => 'pengembangan gemar membaca',
                'kepada' => 'Kepala Sekolah',
                'up' => '',
            ],
            (object)[
                'id' => 2,
                'no_surat' => '002',
                'sifat' => 'penting',
                'jenis' => 'undangan',
                'hal' => 'penting',
                'tgl_surat' => '17 Juli 2025',
                'klasifikasi' => 'pengembangan gemar membaca',
                'kepada' => 'Kepala Sekolah',
                'up' => '',
            ],
            (object)[
                'id' => 3,
                'no_surat' => '003',
                'sifat' => 'penting',
                'jenis' => 'undangan',
                'hal' => 'penting',
                'tgl_surat' => '17 Juli 2025',
                'klasifikasi' => 'pengembangan gemar membaca',
                'kepada' => 'Kepala Sekolah',
                'up' => '',
            ],
            (object)[
                'id' => 4,
                'no_surat' => '004',
                'sifat' => 'penting',
                'jenis' => 'undangan',
                'hal' => 'penting',
                'tgl_surat' => '17 Juli 2025',
                'klasifikasi' => 'pengembangan gemar membaca',
                'kepada' => 'Kepala Sekolah',
                'up' => '',
            ],
            (object)[
                'id' => 5,
                'no_surat' => '005',
                'sifat' => 'penting',
                'jenis' => 'undangan',
                'hal' => 'penting',
                'tgl_surat' => '17 Juli 2025',
                'klasifikasi' => 'pengembangan gemar membaca',
                'kepada' => 'Kepala Sekolah',
                'up' => '',
            ],
        ];

        return view('suratterkirim.index', compact('suratTerkirim'));
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
    public function show(string $id)
    {
        $suratTerkirim = SuratTerkirim::findOrFail($id);
        return view('suratterkirim.show', compact('suratTerkirim'));
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
        $suratTerkirim = SuratTerkirim::findOrFail($id);
        $suratTerkirim->delete();

        return redirect()->route('suratterkirim.index')->with('success', 'Surat Terkirim berhasil dihapus.');
    }
}
