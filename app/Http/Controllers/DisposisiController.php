<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disposisi; // Assuming you have a Disposisi model

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Dummy data for the list view
        $disposisiList = [
            (object)[ // Cast array to object to mimic model behavior ($disposisi->id)
                'id' => 1,
                'no_agenda' => '17004',
                'no_surat' => '400.2/24/109.4/2024',
                'sifat' => 'Biasa',
                'jenis' => 'Surat Masuk',
                'dari' => 'Dinas Pemberdayaan Perempuan Perlindungan Anak dan Kependudukan Prov Jatim',
                'tujuan' => 'Asisten Administrasi Umum',
                'hal' => 'Penyelenggaraan PHI Prov Jatim Ke 96 Tahun 2024',
                'tgl_surat' => '2024-10-15', // Use YYYY-MM-DD format for Carbon parsing
            ],
            (object)[
                'id' => 2,
                'no_agenda' => '17003',
                'no_surat' => '001/SMK/2025', // Example from Surat Keluar image
                'sifat' => 'Penting', // Example from Surat Keluar image
                'jenis' => 'Resmi', // Example from Surat Keluar image
                'dari' => 'PUJI ASTUTIK JI. Hanjung 448 RT 05 RW 01 Kepung Kab. Kediri Telp. 0823 3451 1250',
                'tujuan' => 'Asisten Pemerintahan dan Kesejahteraan Rakyat',
                'hal' => 'Permohonan Izin', // Example from Surat Keluar image
                'tgl_surat' => '2025-07-15', // Example from Surat Keluar image
            ],
            (object)[
                'id' => 3,
                'no_agenda' => '17002',
                'no_surat' => '400.15.1 /KL-Prov.Jatim/X/2024',
                'sifat' => 'Biasa',
                'jenis' => 'Surat Masuk',
                'dari' => 'Komisi Informasi Prov.Jatim',
                'tujuan' => 'Yth. Bp. Pj. Gubernur Jawa Timur',
                'hal' => 'Permohonan Audiensi',
                'tgl_surat' => '2024-10-15',
            ],
            (object)[
                'id' => 4,
                'no_agenda' => '17001',
                'no_surat' => '975/Pdt.G/2024/PN.Sby',
                'sifat' => 'Biasa',
                'jenis' => 'Surat Masuk',
                'dari' => 'Pengadilan Negeri Surabaya',
                'tujuan' => 'Asisten Pemerintahan dan Kesejahteraan Rakyat',
                'hal' => 'Relaks panggilan kepada Turut Tergugat II (Surat Tercatat)',
                'tgl_surat' => '2024-10-15',
            ],
            (object)[
                'id' => 5,
                'no_agenda' => '17000',
                'no_surat' => '100.3.1/1750/35.09/2024',
                'sifat' => 'Biasa',
                'jenis' => 'Surat Masuk',
                'dari' => 'Sekretariat Dewan Perwakilan Rakyat Daerah Kabupaten Jember',
                'tujuan' => 'Biro Pemerintahan dan Otonomi',
                'hal' => 'Penyampaian Risalah Rapat Paripurna',
                'tgl_surat' => '2024-10-15',
            ],
            // Add more dummy data as needed, ensuring 'id' is unique and present
        ];

        return view('disposisi.index', compact('disposisiList'));
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
        // Dummy data for detail view (you can expand on this)
        $allDummyData = [
            (object)[
                'id' => 1,
                'no_agenda' => '17004',
                'no_surat' => '400.2/24/109.4/2024',
                'sifat' => 'Biasa',
                'jenis' => 'Surat Masuk',
                'dari' => 'Dinas Pemberdayaan Perempuan Perlindungan Anak dan Kependudukan Prov Jatim',
                'tujuan' => 'Asisten Administrasi Umum',
                'hal' => 'Penyelenggaraan PHI Prov Jatim Ke 96 Tahun 2024',
                'tgl_surat' => '2024-10-15',
                'unit_pengirim' => 'Sub Bagian Persuratan dan Arsip', // Added for detail view
                'isi_disposisi' => 'Mohon segera ditindaklanjuti dan buat laporan.',
                'catatan' => 'Penting untuk segera diselesaikan.',
            ],
            (object)[
                'id' => 2,
                'no_agenda' => '17003',
                'no_surat' => '001/SMK/2025',
                'sifat' => 'Penting',
                'jenis' => 'Resmi',
                'dari' => 'PUJI ASTUTIK JI. Hanjung 448 RT 05 RW 01 Kepung Kab. Kediri Telp. 0823 3451 1250',
                'tujuan' => 'Asisten Pemerintahan dan Kesejahteraan Rakyat',
                'hal' => 'Permohonan Izin',
                'tgl_surat' => '2025-07-15',
                'unit_pengirim' => 'Kepala Sekolah',
                'isi_disposisi' => 'Untuk diketahui dan disiapkan rapat koordinasi.',
                'catatan' => 'Segera siapkan agenda rapat.',
            ],
            // Add more dummy data corresponding to the IDs you use in the list
        ];

        // Find the specific dummy data item by ID
        $disposisi = collect($allDummyData)->firstWhere('id', $id);

        if (!$disposisi) {
            return redirect()->route('disposisi.index')->with('error', 'Disposisi tidak ditemukan.');
        }

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
}
