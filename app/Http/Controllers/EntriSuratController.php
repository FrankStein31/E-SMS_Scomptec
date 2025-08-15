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
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;


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
            if ($scan->nama_file && Storage::disk('public_uploads')->exists($scan->nama_file)) {
                Storage::disk('public_uploads')->delete($scan->nama_file);
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
        $userId = Auth::user()->id;
        if ($request->ajax()) {
            // Jika tidak ada filter unit_pengentri yang dipilih, maka tampilkan semua
            // Atau jika Anda ingin membatasi hanya entri surat milik user yang login, 
            // uncomment baris berikut:
            // if (!$request->has('unit_pengentri') || $request->get('unit_pengentri') == '') {
            //     $request->merge(['unit_pengentri' => $userId]);
            // }
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
        $data = EntrySuratIsi::with('FileScan', 'tujuanSurat')->find($id);

        if (!$data) {
            return redirect()->route('entrisurat.index')->with('error', 'Data tidak ditemukan');
        }

        // Debug data
        // dd($data->toArray());

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

        // Get selected kepada users
        $selectedKepada = [];
        if ($data->tujuanSurat && count($data->tujuanSurat) > 0) {
            foreach ($data->tujuanSurat as $tujuan) {
                $selectedKepada[] = $tujuan->userid_tujuan;
            }
        }

        return view('entrisurat.edit', compact(
            'data',
            'users',
            'klasifikasi',
            'jenisSurat',
            'default_jenis_surat_last_id',
            'selectedKepada'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = EntrySuratIsi::find($id);

        if (!$data) {
            return redirect()->route('entrisurat.index')->with('error', 'Data tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            $updateData = [
                'nomor_surat' => $request->no_surat,
                'tgl_surat' => $request->tgl_surat,
                'tgl_diterima' => $request->tgl_terima,
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

            // Update kepada
            if ($request->kepada) {
                $kepada = "";
                foreach ($request->kepada as $key => $value) {
                    $user = User::find($value);
                    if ($user) {
                        $kepada .= $user->fullname . ",";
                    }
                }
                $updateData['kepada'] = rtrim($kepada, ',');

                // Delete existing tujuan and recreate
                EntrySuratTujuan::where('entrysurat_id', $id)->delete();

                foreach ($request->kepada as $key => $value) {
                    $user = User::find($value);
                    if ($user) {
                        $satker = MasterSatker::where('userid', $user->id)->first();
                        if ($satker) {
                            EntrySuratTujuan::create([
                                'satkerid_tujuan' => $satker->satkerid,
                                'dibaca' => 0,
                                'is_tembusan' => 0,
                                'entrysurat_id' => $id,
                                'userid_tujuan' => $user->id,
                            ]);
                        }
                    }
                }
            }

            $data->update($updateData);

            DB::commit();
            return redirect()->route('entrisurat.index')->with('success', 'Data berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = EntrySuratIsi::find($id);

        if (!$data) {
            return redirect()->route('entrisurat.index')->with('error', 'Data tidak ditemukan');
        }

        DB::beginTransaction();
        try {
            // Delete related file scans
            $fileScans = EntrySuratScan::where('entrysurat_id', $id)->get();
            foreach ($fileScans as $scan) {
                if ($scan->file_path && Storage::disk('public')->exists($scan->file_path)) {
                    Storage::disk('public')->delete($scan->file_path);
                }
                $scan->delete();
            }

            // Delete related tujuan
            EntrySuratTujuan::where('entrysurat_id', $id)->delete();

            // Delete main record
            $data->delete();

            DB::commit();
            return redirect()->route('entrisurat.index')->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('entrisurat.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cetak tanda terima surat
     */
    public function cetakTandaTerima($id)
    {
        $data = EntrySuratIsi::with(['jenis', 'klasifikasi', 'createdBy', 'tujuan.user'])
            ->findOrFail($id);

        return view('entrisurat.cetak.tanda-terima', compact('data'));
    }

    /**
     * Cetak surat
     */
    public function cetakSurat($id)
    {
        $data = EntrySuratIsi::with(['jenis', 'klasifikasi', 'createdBy', 'tujuan.user', 'FileScan'])
            ->findOrFail($id);

        return view('entrisurat.cetak.surat', compact('data'));
    }

    /**
     * Cetak disposisi surat
     */
    public function cetakDisposisi($id)
    {
        $data = EntrySuratIsi::with(['jenis', 'klasifikasi', 'createdBy', 'tujuan.user', 'disposisi.tindakan'])
            ->findOrFail($id);

        return view('entrisurat.cetak.disposisi', compact('data'));
    }

    /**
     * Export Tanda Terima Surat ke Word
     */
    public function exportTandaTerimaWord($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection([
            'marginTop' => 600,
            'marginLeft' => 600,
            'marginRight' => 600,
            'marginBottom' => 600,
        ]);

        // Header: Logo + Kop Surat
        $headerTable = $section->addTable(['alignment' => 'center']);
        $headerTable->addRow();
        $logoPath = public_path('assets/images/logo.png');
        if (file_exists($logoPath)) {
            try {
                $headerTable->addCell(1200)->addImage($logoPath, [
                    'width' => 70,
                    'height' => 70,
                    'alignment' => 'center'
                ]);
            } catch (\Exception $e) {
                $headerTable->addCell(1200)->addText('Logo gagal dimuat');
            }
        } else {
            $headerTable->addCell(1200)->addText('Logo tidak ditemukan');
        }
        $kopCell = $headerTable->addCell(8000, ['borderBottomSize' => 12, 'borderBottomColor' => '000000']);
        $kopCell->addText('PEMERINTAH PROVINSI JAWA TIMUR', ['bold' => true, 'size' => 16], ['alignment' => 'center']);
        $kopCell->addText('SEKRETARIAT DAERAH', ['bold' => true, 'size' => 14], ['alignment' => 'center']);
        $kopCell->addText('JL. Pahlawan 110, Surabaya, Jawa Timur', ['size' => 11], ['alignment' => 'center']);
        $kopCell->addText('Telp (031) 3524001 - 11, Pswt 1467-1465-1489', ['size' => 11], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Judul
        $section->addText('TANDA PENERIMAAN SURAT', ['bold' => true, 'size' => 14, 'underline' => 'single'], ['alignment' => 'center']);
        $section->addTextBreak(1);

        // Tabel info surat
        $table = $section->addTable(['cellMargin' => 40]);
        $table->addRow();
        $table->addCell(3000)->addText('Telah Terima Surat dari');
        $table->addCell(200)->addText(':');
        $table->addCell(5000)->addText($data->dari);
        $table->addRow();
        $table->addCell(3000)->addText('Tanggal');
        $table->addCell(200)->addText(':');
        $table->addCell(5000)->addText(date('d/m/Y', strtotime($data->tgl_surat)));
        $table->addRow();
        $table->addCell(3000)->addText('Nomor Surat');
        $table->addCell(200)->addText(':');
        $table->addCell(5000)->addText($data->nomor_surat);
        $table->addRow();
        $table->addCell(3000)->addText('Perihal');
        $table->addCell(200)->addText(':');
        $table->addCell(5000)->addText($data->hal);
        $table->addRow();
        $table->addCell(3000)->addText('Diterima');
        $table->addCell(200)->addText(':');
        $table->addCell(5000)->addText(date('d-m-Y', strtotime($data->tgl_diterima)));

        // Tanda tangan di kanan bawah
        $section->addTextBreak(3);
        $footerTable = $section->addTable(['alignment' => 'right']);
        $footerTable->addRow();
        $footerCell = $footerTable->addCell(5000);
        $footerCell->addText('SURABAYA, ' . date('d-m-Y', strtotime($data->tgl_diterima)), [], ['alignment' => 'right']);
        $footerCell->addText('PENERIMA', [], ['alignment' => 'right']);
        $footerCell->addTextBreak(2);
        $footerCell->addText($data->createdBy->fullname ?? '-', ['bold' => true, 'underline' => 'single'], ['alignment' => 'right']);
        $footerCell->addText('NIP. ........................................', [], ['alignment' => 'right']);

        $fileName = 'tanda-terima-' . $data->nomor_surat . '.docx';
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }



    public function exportSuratWord($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // Set default font for a cleaner look, as seen in the example image
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 900,
            'marginLeft' => 1000,
            'marginRight' => 900,
            'marginBottom' => 900,
        ]);

        // Define table styles for consistency
        $phpWord->addTableStyle('HeaderTable', [
            'alignment' => 'center',
            'cellMargin' => 60,
            'borderSize' => 0,
        ]);
        $phpWord->addTableStyle('MainTable', [
            'alignment' => 'left',
            'cellMargin' => 80,
            'borderSize' => 0,
        ]);

        // Header: Logo + Kop Surat
        $headerTable = $section->addTable('HeaderTable');
        $headerTable->addRow();

        // Logo cell
        $logoCell = $headerTable->addCell(1500, ['valign' => 'center']);
        $logoPathCandidates = [
            public_path('assets/images/logo.png'),
            public_path('assets/images/logo.png'),
            public_path('assets/images/logo.jpg'),
        ];
        $logoPlaced = false;
        foreach ($logoPathCandidates as $logoPath) {
            if (file_exists($logoPath)) {
                try {
                    $logoCell->addImage($logoPath, [
                        'width' => 70,
                        'height' => 70,
                        'alignment' => 'center',
                    ]);
                    $logoPlaced = true;
                    break;
                } catch (\Exception $e) {
                    // fallback to text if image fails
                }
            }
        }
        if (!$logoPlaced) {
            $logoCell->addText('LOGO', ['bold' => true]);
        }

        // Kop surat cell
        $kopCell = $headerTable->addCell(8500, [
            'valign' => 'center',
            'borderBottomSize' => 12,
            'borderBottomColor' => '000000',
        ]);
        $kopCell->addText('PEMERINTAH PROVINSI JAWA TIMUR', ['bold' => true, 'size' => 16], ['alignment' => 'center', 'spaceAfter' => 0]);
        $kopCell->addText('SEKRETARIAT DAERAH', ['bold' => true, 'size' => 14], ['alignment' => 'center', 'spaceAfter' => 0]);
        $kopCell->addText('Jl. Rajawali 6 - 8, Surabaya 60176', ['size' => 11], ['alignment' => 'center', 'spaceAfter' => 0]);
        $kopCell->addText('Telp (031) 3524001 - 11, Pswt 1467-1465-1489', ['size' => 11], ['alignment' => 'center', 'spaceAfter' => 0]);

        // Garis tebal horizontal
        $section->addLine([
            'weight' => 2.5,
            'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(16),
            'color' => '000000',
        ]);
        $section->addTextBreak(1);

        // --- Bagian Informasi Surat dan Alamat Tujuan ---
        $mainTable = $section->addTable(['width' => 10000, 'unit' => 'dxa']);

        // Baris 1: Nomor Surat & Tanggal di kanan
        $mainTable->addRow();
        $mainTable->addCell(5000)->addText('No Surat : ' . ($data->nomor_surat ?? '-'), null, ['spaceAfter' => 0]);
        $mainTable->addCell(5000)->addText('Surabaya, ' . ($data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : date('d/m/Y')), null, ['alignment' => 'right', 'spaceAfter' => 0]);

        // Baris 2: Klasifikasi & Kepada Yth.
        $mainTable->addRow();
        $mainTable->addCell(5000)->addText('Klasifikasi : ' . ($data->kode_klasifikasi ?? '-'), null, ['spaceAfter' => 0]);
        $kepadaCell = $mainTable->addCell(5000, ['valign' => 'top']);
        $kepadaCell->addText('Kepada Yth. ' . ($data->kepada ?? '-'), null, ['alignment' => 'right', 'spaceAfter' => 0]);

        // Baris 3: Hal & di Tempat
        $mainTable->addRow();
        $mainTable->addCell(5000)->addText('Hal : ' . ($data->hal ?? '-'), null, ['spaceAfter' => 0]);
        $diTempatCell = $mainTable->addCell(5000, ['valign' => 'top']);
        $diTempatCell->addText('di Tempat', null, ['alignment' => 'right', 'spaceAfter' => 0]);

        // Baris 4: Sifat Surat & Kosong
        $mainTable->addRow();
        $mainTable->addCell(5000)->addText('Sifat Surat : ' . ($data->sifat ?? '-'), null, ['spaceAfter' => 0]);
        $mainTable->addCell(5000)->addText('');

        // Baris 5: Dari & Kosong
        $mainTable->addRow();
        $mainTable->addCell(5000)->addText('Dari : ' . ($data->dari ?? '-'), null, ['spaceAfter' => 0]);
        $mainTable->addCell(5000)->addText('');

        // Baris 6: Kosong & Kosong
        if (!empty($data->jumlah_lampiran)) {
            $mainTable->addRow();
            $mainTable->addCell(5000)->addText('Lampiran : ' . $data->jumlah_lampiran, null, ['spaceAfter' => 0]);
            $mainTable->addCell(5000)->addText('');
        }

        $section->addTextBreak(1);

        // Isi ringkas / konten utama
        if (!empty($data->isi)) {
            $section->addText($data->isi, [], ['alignment' => 'both', 'spaceAfter' => 200]);
        }

        $section->addTextBreak(3);

        // Tembusan dan Tanda tangan
        $footerTable = $section->addTable(['width' => 10000, 'unit' => 'dxa']);
        $footerTable->addRow();

        // Tembusan (kolom kiri)
        $tembusanCell = $footerTable->addCell(5000);
        if (!empty($data->tembusan)) {
            $tembusanCell->addText('Tembusan', ['bold' => true]);
            $items = preg_split('/\r\n|\r|\n|,|;/', (string) $data->tembusan);
            if (is_array($items)) {
                foreach ($items as $item) {
                    $item = trim($item);
                    if ($item !== '') {
                        $tembusanCell->addText('- ' . $item);
                    }
                }
            }
        }

        // Tanda tangan (kolom kanan)
        $signCell = $footerTable->addCell(5000, ['valign' => 'top']);
        $signCell->addText('Dari', ['bold' => true], ['alignment' => 'right']);
        $signCell->addText('Komisi Informasi Prov Jawa Timur', ['underline' => 'single'], ['alignment' => 'right']);

        $fileName = 'surat-' . ($data->nomor_surat ?? 'tanpa-nomor') . '.docx';
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }



    public function exportSuratDisWord($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        // Set default font for a cleaner look
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop' => 900,
            'marginLeft' => 900,
            'marginRight' => 900,
            'marginBottom' => 900,
        ]);

        // Define table styles
        $phpWord->addTableStyle('MainTable', [
            'cellMargin' => 50,
            'borderSize' => 6,
            'borderColor' => '000000',
        ]);

        // Kop surat
        $section->addText('PEMERINTAH PROVINSI JAWA TIMUR', ['bold' => true, 'size' => 12], ['alignment' => 'center', 'spaceAfter' => 0]);
        $section->addText('SEKRETARIAT DAERAH', ['bold' => true, 'size' => 12], ['alignment' => 'center', 'spaceAfter' => 0]);

        // Garis pemisah
        $section->addLine([
            'weight' => 2.5,
            'width' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(16),
            'color' => '000000',
        ]);

        $section->addText('LEMBAR DISPOSISI', ['bold' => true, 'size' => 14], ['alignment' => 'center', 'spaceAfter' => 200]);

        // Main Disposisi Table
        $table = $section->addTable('MainTable');

        // First row: Surat dari & Klasifikasi
        $table->addRow();
        $table->addCell(4000)->addText('Surat dari', ['bold' => false]);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(5000)->addText($data->dari ?? '-');
        $table->addCell(4000)->addText('Klasifikasi', ['bold' => false], ['alignment' => 'right']);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(2000)->addText('000 /     /     /2020', null, ['spaceAfter' => 0]);

        // Second row: Tanggal surat & Diterima tanggal
        $table->addRow();
        $table->addCell(4000)->addText('Tanggal surat', ['bold' => false]);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(5000)->addText($data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-');
        $table->addCell(4000)->addText('Diterima tanggal', ['bold' => false], ['alignment' => 'right']);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(2000)->addText(date('d/m/Y'));

        // Third row: Nomor & Nomor Agenda
        $table->addRow();
        $table->addCell(4000)->addText('Nomor', ['bold' => false]);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(5000)->addText($data->nomor_surat ?? '-');
        $table->addCell(4000)->addText('Nomor Agenda', ['bold' => false], ['alignment' => 'right']);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(2000)->addText('');

        // Fourth row: Hal & Diteruskan kepada
        $table->addRow();
        $table->addCell(4000)->addText('Hal', ['bold' => false]);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(5000)->addText($data->hal ?? '-');
        $table->addCell(4000)->addText('Diteruskan kepada', ['bold' => false], ['alignment' => 'right']);
        $table->addCell(200)->addText(':', ['bold' => false]);
        $table->addCell(2000)->addText('1. Yth. Bp. Pj. Gubernur Jawa Timur,');

        // Fifth row: Kosong & Daftar penerus
        $table->addRow();
        $table->addCell(5200)->addText('');
        $table->addCell(200)->addText('2.');
        $table->addCell(2000)->addText('');
        $table->addCell(200)->addText('3.');
        $table->addCell(2000)->addText('');
        $table->addCell(200)->addText('4.');
        $table->addCell(2000)->addText('');
        $table->addCell(200)->addText('5.');
        $table->addCell(2000)->addText('');

        $section->addTextBreak(1);
        $section->addText('ISI DISPOSISI', ['bold' => true, 'size' => 14], ['alignment' => 'center', 'spaceAfter' => 200]);

        // ISI DISPOSISI Table
        $isiDisposisiTable = $section->addTable(['width' => 10000, 'unit' => 'dxa', 'borderSize' => 6, 'borderColor' => '000000']);
        $isiDisposisiTable->addRow(5000); // 5cm height
        $isiDisposisiTable->addCell(10000)->addText('');

        $fileName = 'disposisi-' . ($data->nomor_surat ?? 'tanpa-nomor') . '.docx';
        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
