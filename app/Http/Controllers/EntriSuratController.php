<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EntrySuratIsi;
use App\Models\EntrySuratScan;
use App\Models\EntrySuratTujuan;
use App\Models\MasterJenisSurat;
use App\Models\MasterKlasifikasi;
use App\Models\MasterSatker;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\DataTables\EntrySuratIsiDataTable;

class EntriSuratController extends Controller
{

    function saveBase64Image($base64Image, $folder = 'uploads_file_scan', $filename = null)
    {
        // Cek dan pecah base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
            $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
            $extension = strtolower($type[1]); // jpg, png, gif, etc.

            // Validasi ekstensi
            if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                throw new \Exception('Invalid image type');
            }
        } else {
            throw new \Exception('Invalid base64 image');
        }

        // Decode base64
        $base64Image = str_replace(' ', '+', $base64Image);
        $imageData = base64_decode($base64Image);

        if (!$imageData) {
            throw new \Exception('Base64 decoding failed');
        }

        // Generate nama file
        $filename = $filename ?? uniqid() . '.' . $extension;

        // Simpan gambar (gunakan storage/app/public/uploads)
        $path = $folder . '/' . $filename;
        Storage::disk('public_uploads')->put($path, $imageData);

        return $path; // return path relatif
    }

    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function scanfile(Request $request, $entri_surat_id)
    {
        DB::beginTransaction();
        try {
            $file = self::saveBase64Image($request->images_input, 'uploads_file_scan');
            $entriScan = EntrySuratScan::create([
                'entrysurat_id' => $entri_surat_id,
                'nourut' => EntrySuratScan::where('entrysurat_id', $entri_surat_id)->count() + 1,
                'nama_scan' => $file,
                'nama_file' => $file,
                'size' => 0,
                'tgl_upload' => date('Y-m-d')
            ]);
            DB::commit();
            return redirect()->back()->with('success', "Berhasil Menyimpan file scan");
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('danger', "Terjadi kesalahan saat menyimpan file scan");
        }
    }

    public function deleteScan($id)
    {
        $scan = \App\Models\EntrySuratScan::find($id);
        if (!$scan) {
            return response()->json(['success' => false, 'message' => 'File scan tidak ditemukan']);
        }
        // Hapus file dari storage
        try {
            if ($scan->nama_file && \Storage::disk('public_uploads')->exists($scan->nama_file)) {
                \Storage::disk('public_uploads')->delete($scan->nama_file);
            }
            $scan->delete();
            return response()->json(['success' => true, 'message' => 'Berhasil hapus file scan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal hapus file scan!']);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, EntrySuratIsiDataTable $dataTable)
    {
        if ($request->ajax()) {
            return $dataTable->ajax();
        }
        return $dataTable->render('entrisurat.index');
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
        $klasifikasi = MasterKlasifikasi::all();
        $jenisSurat = MasterJenisSurat::all();
        $default_jenis_surat = MasterJenisSurat::where('name', 'Surat Masuk')->first();
        $default_jenis_surat_last_id = $default_jenis_surat ? $default_jenis_surat->last_id : 0;
        return view('entrisurat.create', compact(
            'users',
            'klasifikasi',
            'jenisSurat',
            'default_jenis_surat_last_id'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $noagenda = EntrySuratIsi::whereYear('tgl_diarahkan', date('Y'))->max('noagenda') + 1;
        DB::beginTransaction();
        try {
            $data = [
                'nomor_surat' => $request->no_surat,
                'noagenda' => $noagenda,
                'tgl_diarahkan' => date('Y-m-d'),
                'tgl_surat' => $request->tgl_surat,
                'tgl_diterima' => $request->tgl_terima,
                'created_by' => Auth::user()->id ?? 190,
                'updated_by' => Auth::user()->id ?? 190,
                'hal' => $request->hal,
                'dari' => $request->dari,
                'alamat' => $request->alamat,
                'sifat' => $request->sifat,
                'isi' => $request->ringkasan,
                'tembusan' => $request->tembusan,
                'jumlah_lampiran' => $request->lampiran,
                'jenis_id' => $request->jenis_surat,
                'kode_klasifikasi' => $request->klasifikasi,
            ];

            $kepada = "";
            foreach ($request->kepada as $key => $value) {
                $user = User::find($value);
                if ($user) {
                $kepada .= $user->fullname . ",";
                }
            }
            $data['kepada'] = rtrim($kepada, ',');

            $create = EntrySuratIsi::create($data);

            foreach ($request->kepada as $key => $value) {
                $user = User::find($value);
                if ($user) {
                $satker = MasterSatker::where('userid', $user->id)->first();
                    if ($satker) {
                $tujuan = EntrySuratTujuan::create([
                    'satkerid_tujuan' => $satker->satkerid,
                    'dibaca' => 0,
                    'is_tembusan' => 0,
                    'entrysurat_id' => $create->id,
                    'userid_tujuan' => $user->id,
                ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('entrisurat.show', $create->id)
                ->with('success', 'Berhasil membuat entri surat.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('danger', 'Gagal Membuat Entri Surat: ' . $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $data = EntrySuratIsi::with('FileScan')->find($id);
        return view('entrisurat.show', compact('data'));
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
