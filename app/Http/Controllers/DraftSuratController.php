<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DraftSurat;
use Illuminate\Support\Str;

class DraftSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drafts = session('draft_surats', []);
        return view('draft_surat.index', compact('drafts'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('draft_surat.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
            'isi'   => 'required|string',
        ]);

        // Ambil data draft dari session (jika ada)
        $drafts = session()->get('draft_surats', []);

        // Tambahkan draft baru ke array
        $drafts[] = [
            'id'    => Str::uuid(), // butuh `use Illuminate\Support\Str;`
            'judul' => $request->judul,
            'isi'   => $request->isi,
            'waktu' => now()->format('Y-m-d H:i:s'),
        ];

        // Simpan kembali ke session
        session(['draft_surats' => $drafts]);

        return redirect()->route('draftsurat.index')->with('success', 'Draft berhasil disimpan!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data draft surat berdasarkan id
        $draft = DraftSurat::findOrFail($id);

        // Kirim data ke view
        return view('draft_surat.show', compact('draft'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $draft = DraftSurat::findOrFail($id);
        return view('draft_surat.edit', compact('draft'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'judul' => 'required|string',
            'isi' => 'required|string',
        ]);

        $draft = DraftSurat::findOrFail($id);
        $draft->update($request->only('judul', 'isi'));

        return redirect()->route('draft-surat.index')->with('success', 'Draft berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DraftSurat::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Draft berhasil dihapus.');
    }
}
