<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = User::select([
            'id',
            'FullName',
            'Jabatan as Jabatan2',
            'UserName',
            DB::raw("
                CASE 
                    WHEN (SELECT COUNT(b.userid) FROM master_satkers b WHERE b.userid = users.id) = 0 
                    THEN users.FullName 
                    ELSE users.Jabatan 
                END AS Jabatan
            ")
        ])->get();

        // Ambil semua jenis surat untuk filter dropdown dan mapping id => name
        $jenisSuratList = DB::table('master_jenis_surats')->get();
        $jenisSuratMap = [];
        foreach ($jenisSuratList as $row) {
            $jenisSuratMap[$row->id] = $row->name;
            $jenisSuratMap[$row->last_id] = $row->name;
        }

        // Ambil data surat masuk dan keluar, mapping id surat ke jenis_id
        $entrySuratJenis = DB::table('entry_surat_isis')->pluck('jenis_id', 'id')->toArray();
        $keluarSuratJenis = DB::table('surat_keluar_isis')->pluck('jenis_id', 'id')->toArray();

        // Ambil filter dari request
        $jenisId = $request->input('jenis_id');

        // Query disposisi dengan relasi entrysurat dan jenis surat
        $query = \App\Models\Disposisi::with([
            'entrysurat',
            'parent',
            'pembuat'
        ])->whereHas('entrysurat', function ($q) use ($jenisId, $entrySuratJenis, $keluarSuratJenis) {
            if ($jenisId) {
                $q->where(function ($sub) use ($jenisId, $entrySuratJenis, $keluarSuratJenis) {
                    $sub->whereIn('id', array_keys(array_filter($entrySuratJenis, fn($v) => $v == $jenisId)))
                        ->orWhereIn('id', array_keys(array_filter($keluarSuratJenis, fn($v) => $v == $jenisId)));
                });
            }
        });

        $disposisiList = $query->latest()->get();

        return view('disposisi.index', compact(
            'disposisiList',
            'jenisSuratMap',
            'jenisId',
            'users',
            'entrySuratJenis',
            'keluarSuratJenis'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil surat yang bisa didisposisikan
        $suratList = DB::table('entry_surat_isis')->orderBy('created_at', 'desc')->get();
        return response()->json($suratList); // Untuk kebutuhan AJAX modal
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'entrysurat_id' => 'required',
            'kepada' => 'required|array|min:1',
            'kepada.*' => 'exists:users,id',
            'hal' => 'nullable|string|max:255',
            'isi' => 'nullable|string',
            'tindakan' => 'nullable|string|max:255',
        ]);

        // Parse id
        $entrysurat_id = $request->entrysurat_id;
        if (str_starts_with($entrysurat_id, 'entry_')) {
            $real_id = substr($entrysurat_id, 6);
        } else {
            $real_id = $entrysurat_id;
        }

        // Ambil kodeklasifikasi dari EntrySuratIsi
        $entrySurat = \App\Models\EntrySuratIsi::find($real_id);
        $kodeklasifikasi = $entrySurat ? $entrySurat->kode_klasifikasi : '-';

        // Gabungkan nama user yang dipilih menjadi string
        $kepada_nama = '';
        foreach ($request->kepada as $userId) {
            $user = \App\Models\User::find($userId);
            if ($user) {
                $kepada_nama .= $user->FullName . ',';
            }
        }
        $kepada_nama = rtrim($kepada_nama, ',');

        // Gabungkan ID user menjadi string, misal: "1,2,3"
        $kepada_ids = implode(',', $request->kepada);

        $disposisi = \App\Models\Disposisi::create([
            'id' => (string) \Illuminate\Support\Str::ulid(),
            'entrysurat_id' => $real_id,
            'kepada' => $kepada_ids,
            'hal' => $request->hal,
            'isi' => $request->isi,
            'tindakan' => $request->tindakan,
            'kodeklasifikasi' => $kodeklasifikasi, // <-- ambil dari surat
            'userid_pembuat' => Auth::user()->id,
            'satkerid_pembuat' => Auth::user()->masterSatker->kodesatker ?? 0,
            'tgl_disposisi' => now(),
            'status' => 0,
        ]);

        return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    { // Ambil data berdasarkan ID ULID
        $disposisi = Disposisi::findOrFail($id);

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
