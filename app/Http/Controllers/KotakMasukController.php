<?php

namespace App\Http\Controllers;

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

class KotakMasukController extends Controller
{

    /**
     * disposisi
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function disposisi($id)
    {
        // Ambil kodesatker user login
        $loginUser = Auth::user();
        $loginSatker = \App\Models\MasterSatker::where('userid', $loginUser->id)->first();
        $kodesatker = $loginSatker ? $loginSatker->kodesatker : null;

        // Ambil user yang satu bagian (prefix kodesatker sama, dan bukan dirinya sendiri)
        $users = User::whereHas('masterSatker', function($q) use ($kodesatker) {
            if ($kodesatker) {
                $q->where('kodesatker', 'like', $kodesatker . '%')
                  ->whereRaw('LENGTH(kodesatker) > ?', [strlen($kodesatker)]);
            }
        })
        ->where('id', '!=', $loginUser->id)
        ->select([
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
        $master_tindakan_disposisi = MasterTindakanDisposisi::all();
        $data = EntrySuratIsi::find($id);
        return view('kotakmasuk.disposisi', compact(
            'data',
            'users',
            'master_tindakan_disposisi',
            'id'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entrysurat_id' => 'required|exists:entry_surat_isis,id',
            'kepada' => 'required|array',
            'content' => 'nullable|string',
            'tindakan' => 'array',
        ]);

        DB::beginTransaction();
        try {
            // Simpan ke EntrySuratTujuan
            foreach ($request->kepada as $key => $value) {
                $user = User::find($value);
                if (!$user) {
                    \Log::info('User tidak ditemukan', ['user_id' => $value]);
                    continue;
                }
                $satker = MasterSatker::where('userid', $user->id)->first();
                if (!$satker) {
                    \Log::info('Satker tidak ditemukan', ['user_id' => $user->id]);
                    continue;
                }
                // Cek apakah sudah ada
                $sudahAda = EntrySuratTujuan::where([
                    'entrysurat_id' => $request->entrysurat_id,
                    'userid_tujuan' => $user->id,
                ])->first();
                if (!$sudahAda) {
                    EntrySuratTujuan::create([
                        'satkerid_tujuan' => $satker->satkerid,
                        'dibaca' => 0,
                        'is_tembusan' => 0,
                        'entrysurat_id' => $request->entrysurat_id,
                        'userid_tujuan' => $user->id,
                    ]);
                    \Log::info('Berhasil simpan EntrySuratTujuan', ['user_id' => $user->id, 'satkerid' => $satker->satkerid]);
                } else {
                    \Log::info('EntrySuratTujuan sudah ada', ['user_id' => $user->id, 'entrysurat_id' => $request->entrysurat_id]);
                }
            }

            // Simpan ke DisposisiBaru
            $disposisi = DisposisiBaru::create([
                'entrysurat_id' => $request->entrysurat_id,
                'kepada' => implode(',', $request->kepada),
                'content' => $request->content,
            ]);
            if ($request->has('tindakan')) {
                $disposisi->tindakans()->attach($request->tindakan);
            }

            DB::commit();
            \Log::info('DisposisiBaru berhasil disimpan', ['entrysurat_id' => $request->entrysurat_id]);
            return redirect()->route('disposisi.index')->with('success', 'Disposisi berhasil dibuat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            \Log::error('Gagal Membuat Disposisi', ['error' => $th->getMessage()]);
            return redirect()->back()->with('error', 'Gagal Membuat Disposisi');
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = EntrySuratIsi::orderBy('tgl_surat', 'desc')->whereHas('tujuanSurat', function ($q) {
            $q->where('userid_tujuan', Auth::user()->id);
        })->get();
        return view('kotakmasuk.index', compact('data'));
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

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = EntrySuratIsi::find($id);
        return view('kotakmasuk.show', compact('data'));
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
