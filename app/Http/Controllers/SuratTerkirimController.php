<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluarIsi; // Assuming this is the correct model for sent letters
use App\Models\MasterJenisSurat; // If you need to fetch types of letters
use Illuminate\Support\Facades\Storage; // If you need to handle file storage
use Illuminate\Support\Facades\Auth; // For user authentication
use App\Http\Requests\SuratTerkirimRequest; // If you have a custom request
use App\Http\Controllers\Controller;
use App\Models\SuratTerkirim; // Assuming this is the model for sent letters
use Illuminate\Http\Request;

class SuratTerkirimController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->q;

        $suratTerkirim = SuratKeluarIsi::query(); // Memulai query builder

        // Jika Anda ingin memfilter berdasarkan user yang membuat surat (mirip contoh Anda)
        // $suratTerkirim->where('user_id_pembuat', Auth::id());

        // Logika pencarian
        if ($q) {
            $suratTerkirim->where(function ($query) use ($q) {
                $query->where('nosurat', 'like', "%$q%")
                    ->orWhere('hal', 'like', "%$q%")
                    ->orWhere('klasifikasi', 'like', "%$q%");
                $query->orWhereJsonContains('jenis->name', $q);
            });
        }

        // Urutkan dan paginasi
        $suratTerkirim = $suratTerkirim->orderBy('created_at', 'desc')->paginate(10);
        // (dd($suratTerkirim));

        return view('suratterkirim.index', compact('suratTerkirim'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // IMPORTANT: Validate against the actual column names in your database.
        // Adjust these validation rules based on your 'surat_keluar_isi' table schema.
        $validated = $request->validate([
            'nosurat' => 'required|string|max:255', // Example: 'No. Surat' from UI
            'sifat' => 'required|numeric', // Assuming 'jenis' column instead of 'jenis_id' for direct display
            'hal' => 'required|string|max:255', // Example: 'Hal' from UI
            'tgl_surat' => 'required|date', // Example: 'Tgl. Surat' from UI
            'klasifikasi' => 'required|string|max:255', // Example: 'Klasifikasi' from UI
            'kepada' => 'required|array', // Example: 'Kepada' from UI
            'up' => 'nullable|string|max:255', // Example: 'U.P' from UI (might be 'Nama' or 'Jabatan' combined in a real system)
            'nama' => 'nullable|string|max:255', // Based on Surat Keluar UI
            'jabatan' => 'nullable|string|max:255', // Based on Surat Keluar UI
            'satker' => 'nullable|string|max:255', // Based on Surat Keluar UI
            'tembusan' => 'nullable|string|max:255', // From image
            'referensi' => 'nullable|string|max:255', // From image
            'file_lampiran' => 'required|numeric|min:0',
            'penandatangan' => 'required|exists:users,id',
        ]);

        SuratKeluarIsi::create($validated);

        return redirect()->route('suratterkirim.index')->with('success', 'Surat Terkirim berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        return view('suratterkirim.show', compact('suratTerkirim'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        return view('suratterkirim.edit', compact('suratTerkirim'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // IMPORTANT: Validate against the actual column names in your database.
        // Adjust these validation rules based on your 'surat_keluar_isi' table schema.
        $validated = $request->validate([
            'nosurat' => 'required|string|max:255',
            'sifat' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
            'hal' => 'required|string|max:255',
            'tgl_surat' => 'required|date',
            'klasifikasi' => 'required|string|max:255',
            'kepada' => 'required|string|max:255',
            'up' => 'nullable|string|max:255',
            'nama' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'satker' => 'nullable|string|max:255',
            'tembusan' => 'nullable|string|max:255',
            'referensi' => 'nullable|string|max:255',
            'penandatangan' => 'nullable|string|max:255',
            'file_lampiran' => 'nullable|string|max:255',
        ]);

        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        $suratTerkirim->update($validated);

        return redirect()->route('suratterkirim.index')->with('success', 'Surat Terkirim berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        $suratTerkirim->delete();

        return redirect()->route('suratterkirim.index')->with('success', 'Surat Terkirim berhasil dihapus.');
    }

    /**
     * Handle the print action for a specific sent letter.
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function cetak($id)
    {
        $suratTerkirim = SuratKeluarIsi::findOrFail($id);
        // You might have a specific print-friendly view
        return view('suratterkirim.cetak', compact('suratTerkirim'));
    }
}
