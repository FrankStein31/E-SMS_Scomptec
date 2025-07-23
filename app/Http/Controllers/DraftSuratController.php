<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratKeluarIsi;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use App\DataTables\SuratKeluarIsiDataTable;
use Illuminate\Support\Facades\Storage;


class DraftSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SuratKeluarIsiDataTable $dataTable)
    {
        $drafts = SuratKeluarIsi::all();
        // dd($drafts);
        return $dataTable->render('draft_surat.index', compact('drafts'));
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
        // Validasi input
        $validated = $request->validate([
            'jenis_id'      => 'required|string|exists:master_jenis_surats,id',
            'nosurat'       => 'required|string|max:255',
            'klasifikasi'   => 'required|string|max:255',
            'tgl_surat'     => 'required|date',
            'hal'           => 'required|string|max:255',
            'sifat'         => 'required|numeric',
            'lampiran'      => 'required|numeric|min:0',
            'kepada'        => 'required|array',
            'isi'           => 'required|string',
            'tembusan'      => 'nullable|string',
            'referensi'     => 'nullable|numeric',
            'penandatangan' => 'required|exists:users,id',
        ]);

        // Ambil data draft lama dari session, jika tidak ada buat array baru
        $drafts = session('draft_surats', []);

        // Tambahkan draft baru ke dalam array
        $drafts[] = [
            'id'            => (string) Str::uuid(),
            'jenis_id'      => $validated['jenis_id'],
            'nosurat'       => $validated['nosurat'],
            'klasifikasi'   => $validated['klasifikasi'],
            'tgl_surat'     => $validated['tgl_surat'],
            'hal'           => $validated['hal'],
            'sifat'         => $validated['sifat'],
            'lampiran'      => $validated['lampiran'],
            'kepada'        => $validated['kepada'], // akan ditampilkan sebagai array
            'isi'           => $validated['isi'],
            'tembusan'      => Arr::get($validated, 'tembusan'),
            'referensi'     => Arr::get($validated, 'referensi'),
            'penandatangan' => $validated['penandatangan'],
            'created_at'    => now()->toDateTimeString(),
        ];

        // Simpan kembali ke session
        session(['draft_surats' => $drafts]);

        // Redirect ke halaman index draft dengan pesan sukses
        return redirect()
            ->route('draft_surat.index')
            ->with('success', 'Draft surat berhasil disimpan ke dalam session.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Ambil data draft surat berdasarkan id
        $draft = SuratKeluarIsi::findOrFail($id);

        // Kirim data ke view
        return view('draft_surat.show', compact('draft'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $draft = SuratKeluarIsi::findOrFail($id);
        $jenisList = SuratKeluarIsi::all();

        return view('draft_surat.edit', compact('draft', 'jenisList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'no_surat' => 'required|string|max:100',
            'kodeklasifikasi' => 'required|string|max:20',
            'klasifikasi' => 'required|string|max:255',
            'tgl_surat' => 'required|date',
            'hal' => 'required|string|max:255',
            'isi' => 'required|string',
            'tembusan' => 'nullable|string|max:255',
            'referensi' => 'nullable|string|max:255',
            'lampiran_file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $draft = SuratKeluarIsi::findOrFail($id);

        // Update field umum
        $draft->no_surat = $request->no_surat;
        $draft->kodeklasifikasi = $request->kodeklasifikasi;
        $draft->klasifikasi = $request->klasifikasi;
        $draft->tgl_surat = $request->tgl_surat;
        $draft->hal = $request->hal;
        $draft->isi = $request->isi;
        $draft->tembusan = $request->tembusan;
        $draft->referensi = $request->referensi;

        // Handle file lampiran jika ada
        if ($request->hasFile('lampiran_file')) {
            // Hapus file lama jika ada
            if ($draft->lampiran_file && Storage::disk('public')->exists($draft->lampiran_file)) {
                Storage::disk('public')->delete($draft->lampiran_file);
            }

            // Simpan file baru
            $file = $request->file('lampiran_file')->store('lampiran', 'public');
            $draft->lampiran_file = $file;
        }

        $draft->save();

        return redirect()->route('draft_surat.index')->with('success', 'Draft berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SuratKeluarIsi::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Draft berhasil dihapus.');
    }
}
