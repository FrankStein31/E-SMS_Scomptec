<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisSurat;
use App\Models\MasterKlasifikasi;
use App\Models\SuratKeluarIsi;
use App\Models\User;
use App\Repositories\InsertNotaDinas;
use App\Repositories\InsertSuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $suratKeluar = SuratKeluarIsi::with(['jenis'])
            ->where('user_id_pembuat', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buatsurat.index', compact('suratKeluar'));
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
        Log::info('Mulai proses create surat', ['user' => Auth::id()]);
        
        try {
            $validated = $request->validate([
                'jenis_id' => 'required|string|exists:master_jenis_surats,id',
                'nosurat' => 'required|string',
                'klasifikasi' => 'required|string',
                'tgl_surat' => 'required|date',
                'hal' => 'required|string',
                'sifat' => 'required|numeric',
                'lampiran' => 'required|numeric|min:0',
                'kepada' => 'required|array',
                'isi' => 'required|string',
                'tembusan' => 'nullable|string',
                'referensi' => 'nullable|numeric',
                'penandatangan' => 'required|exists:users,id',
            ]);

            Log::info('Validasi data berhasil', ['validated' => $validated]);

        DB::beginTransaction();

            // Generate unique ID
            $id = (string) Str::ulid();
            
            // Get penandatangan info
            $ttdUser = User::findOrFail($request->penandatangan);
            
            // Get jenis_id (last_id) from master_jenis_surat
            $jenisSurat = MasterJenisSurat::where('id', $validated['jenis_id'])->first();
            
            // Create surat
            $surat = new SuratKeluarIsi();
            $surat->id = $id;
            $surat->jenis_id = $jenisSurat->last_id;
            $surat->nosurat = $validated['nosurat'];
            $surat->kodeklasifikasi = $validated['klasifikasi'];
            $surat->tgl_surat = $validated['tgl_surat'];
            $surat->hal = $validated['hal'];
            $surat->jml_lampiran = $validated['lampiran'];
            $surat->sifat = $validated['sifat'];
            $surat->kepada = json_encode($validated['kepada']);
            $surat->isi = $validated['isi'];
            $surat->tembusan = $validated['tembusan'];
            $surat->referensi_id = $validated['referensi'] ?? null;
            $surat->ttd_nama = $ttdUser->fullname;
            $surat->user_ttd_id = $ttdUser->id;
            $surat->user_id_pembuat = Auth::id();
            $surat->satkerid_pembuat = Auth::user()->masterSatker->kodesatker ?? 0;
            
            // Ambil data tujuan dari kepada array
            $kepada = json_decode($validated['kepada'][0], true);
            $surat->user_id_tujuan = $kepada['id'] ?? 0;
            $surat->satkerid_tujuan = 0; // default 0 karena tidak ada di form
            $surat->userid_tujuan = $kepada['id'] ?? 0; // untuk backward compatibility
            
            // Set status dan final
            $surat->status = 1;
            $surat->isfinal = 1;
            $surat->tgl_revisi = now();
            
            // Set field lainnya dengan default
            $surat->dibaca = 0;
            $surat->last_sent = 0; // set ke 0 karena belum dikirim
            $surat->user_id_final = Auth::user()->id;
            $surat->userid_final = Auth::user()->id;
            $surat->satkerid_final = Auth::user()->masterSatker->kodesatker ?? 0;
            
            Log::info('Data surat siap disimpan', ['surat' => $surat->toArray()]);
            
            $surat->save();
            
            // Handle lampiran if exists
            if ($request->hasFile('lampiran_file')) {
                foreach ($request->file('lampiran_file') as $file) {
                    $path = $file->store('surat_keluar', 'public');
                    // Save lampiran to database
                    // ... code untuk simpan lampiran
                    Log::info('File lampiran disimpan', ['path' => $path]);
            }
            }

            DB::commit();
            Log::info('Surat berhasil dibuat', ['id' => $surat->id]);

            return redirect()
                ->route('buatsurat.index')
                ->with('success', 'Surat berhasil dibuat');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat membuat surat', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat surat: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $surat = SuratKeluarIsi::with(['jenis'])->findOrFail($id);
        return view('buatsurat.show', compact('surat'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $surat = SuratKeluarIsi::findOrFail($id);
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
        
        return view('buatsurat.edit', compact(
            'surat',
            'users',
            'userTtd',
            'jenisSurat',
            'klasifikasi'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Log::info('Mulai proses update surat', ['id' => $id, 'user' => Auth::id()]);
        
        try {
            $validated = $request->validate([
                'jenis_id' => 'required|string|exists:master_jenis_surats,id',
                'nosurat' => 'required|string',
                'klasifikasi' => 'required|string',
                'tgl_surat' => 'required|date',
                'hal' => 'required|string',
                'sifat' => 'required|numeric',
                'lampiran' => 'required|numeric|min:0',
                'kepada' => 'required|array',
                'isi' => 'required|string',
                'tembusan' => 'nullable|string',
                'referensi' => 'nullable|numeric',
                'penandatangan' => 'required|exists:users,id',
            ]);

            Log::info('Validasi data berhasil', ['validated' => $validated]);

            DB::beginTransaction();
            
            $surat = SuratKeluarIsi::findOrFail($id);
            
            // Get penandatangan info
            $ttdUser = User::findOrFail($request->penandatangan);
            
            // Get jenis_id (last_id) from master_jenis_surat
            $jenisSurat = MasterJenisSurat::where('id', $validated['jenis_id'])->first();
            
            // Update surat
            $surat->jenis_id = $jenisSurat->last_id;
            $surat->nosurat = $validated['nosurat'];
            $surat->kodeklasifikasi = $validated['klasifikasi'];
            $surat->tgl_surat = $validated['tgl_surat'];
            $surat->hal = $validated['hal'];
            $surat->jml_lampiran = $validated['lampiran'];
            $surat->sifat = $validated['sifat'];
            $surat->kepada = json_encode($validated['kepada']);
            $surat->isi = $validated['isi'];
            $surat->tembusan = $validated['tembusan'];
            $surat->referensi_id = $validated['referensi'] ?? null;
            $surat->ttd_nama = $ttdUser->fullname;
            $surat->user_ttd_id = $ttdUser->id;
            
            // Ambil data tujuan dari kepada array
            $kepada = json_decode($validated['kepada'][0], true);
            $surat->user_id_tujuan = $kepada['id'] ?? 0;
            $surat->userid_tujuan = $kepada['id'] ?? 0;
            
            // Update revisi
            $surat->tgl_revisi = now();
            
            Log::info('Data surat siap diupdate', ['surat' => $surat->toArray()]);
            
            $surat->save();
            
            // Handle lampiran if exists
            if ($request->hasFile('lampiran_file')) {
                foreach ($request->file('lampiran_file') as $file) {
                    $path = $file->store('surat_keluar', 'public');
                    // Save lampiran to database
                    // ... code untuk simpan lampiran
                    Log::info('File lampiran disimpan', ['path' => $path]);
                }
            }

            DB::commit();
            Log::info('Surat berhasil diupdate', ['id' => $surat->id]);

            return redirect()
                ->route('buatsurat.index')
                ->with('success', 'Surat berhasil diupdate');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat update surat', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update surat: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::beginTransaction();
            
            $surat = SuratKeluarIsi::findOrFail($id);
            $surat->delete();
            
            DB::commit();
            
            return redirect()
                ->route('buatsurat.index')
                ->with('success', 'Surat berhasil dihapus');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus surat: ' . $e->getMessage());
        }
    }
}
