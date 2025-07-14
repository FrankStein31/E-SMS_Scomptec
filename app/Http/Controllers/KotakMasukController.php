<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
        $master_tindakan_disposisi = MasterTindakanDisposisi::all();
        $data = EntrySuratIsi::find($id);
        return view('kotakmasuk.disposisi', compact(
            'data',
            'users',
            'master_tindakan_disposisi',
            'id'
        ));
    }

    public function storeDisposisi(Request $request) {
        
        $request->validate([
            'tindakan' => 'required',
            'kepada' => 'required',
            'remitten' => 'required'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->kepada as $key => $value) {
                $user = User::find($value);
                if (!$user) {
                    continue; // skip jika user tidak ditemukan
                }
                $satker = MasterSatker::where('userid', $user->id)->first();
                if (!$satker) {
                    continue; // skip jika satker tidak ditemukan
                }
                
                $tujuan = EntrySuratTujuan::create([
                    'satkerid_tujuan' => $satker->satkerid,
                    'dibaca' => 0,
                    'is_tembusan' => 0,
                    'entrysurat_id' => $request->entrysurat_id,
                    'userid_tujuan' => $user->id,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Berhasil Membuat Disposisi');
        } catch (\Throwable $th) {
            DB::rollBack();
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
    public function store(Request $request)
    {
        //
    }

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
