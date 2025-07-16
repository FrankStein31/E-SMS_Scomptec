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
        $validated = $request->validate([

            'disposisi'        => 'nullable|string',
            'entrisurat_id'    => 'nullable|uuid',
            'parent_id'        => 'nullable|uuid',
            'kepada'           => 'required|string|max:255',
            'tgl_disposisi'    => 'required|date',
            'tgl_remiten'      => 'nullable|date',
            'isi'              => 'required|string',
            'tindakan'         => 'nullable|string|max:255',
            'userid_pembuat'   => 'required|integer|exists:users,id',
            'userid_tujuan'     => 'required|integer|exists:users,id',
            'file_original'    => 'nullable|string|max:255',
            'file_rename'      => 'nullable|string|max:255',
            'file_size'        => 'nullable|integer|min:0',
            'judul'            => 'required|string|max:255',

        ]);

        // Ambil draft dari session
        $drafts = session()->get('draft_surats', []);

        // Tambahkan entri draft baru
        $drafts[] = [
            'id'       => Str::uuid()->toString(),
            'judul'    => $validated['judul'],
            'isi'      => $validated['isi'],
            'waktu'    => now()->toDateTimeString(),
            'pengirim' => $validated['userid_pembuat'],
            'kepada'   => $validated['kepada'],
        ];

        // Simpan kembali ke session
        session(['draft_surats' => $drafts]);

        return redirect()->route('draft_surat.index')->with('success', 'Draft berhasil disimpan!');
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

        return redirect()->route('draft_surat.index')->with('success', 'Draft berhasil diperbarui!');
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
