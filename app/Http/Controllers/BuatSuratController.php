<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisSurat;
use App\Models\MasterKlasifikasi;
use App\Models\User;
use App\Repositories\InsertNotaDinas;
use App\Repositories\InsertSuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BuatSuratController extends Controller
{

    protected $insertNotaDinas;
    protected $insertSuratKeluar;
    public function __construct(InsertNotaDinas $insertNotaDinas, InsertSuratKeluar $insertSuratKeluar)
    {
        $this->insertNotaDinas = $insertNotaDinas;
        $this->insertSuratKeluar = $insertSuratKeluar;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
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
        $userTtd = User::where('usergroupid', '3')->get();
        $jenisSurat = MasterJenisSurat::all();
        $klasifikasi = MasterKlasifikasi::all();
        return view('buatsurat.create', compact(
            'users',
            'userTtd',
            'jenisSurat',
            'klasifikasi'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request->jenis_id == '2') {
                $this->insertNotaDinas->insertNotadinas($request);
            }else{
                // $this->insertSuratKeluar->insertSuratKeluar($request);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Berhasil membuat surat');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            dd($th);
            return redirect()->back()->with('danger', 'Gagal membuat surat');
        }
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
