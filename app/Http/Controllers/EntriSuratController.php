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
     * Export Tanda Terima Surat ke Word dengan Logo Jawa Timur
     */
    public function exportTandaTerimaWord($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Set default font
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Section dengan margin yang sesuai untuk cetak A4
        $section = $phpWord->addSection([
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(29.7),
        ]);

        // Define table styles
        $phpWord->addTableStyle('HeaderTable', [
            'borderSize' => 0,
            'cellMargin' => 50,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
        ]);

        // Header: Logo Jawa Timur + Kop Surat
        $headerTable = $section->addTable('HeaderTable');
        $headerTable->addRow();

        // Logo cell - prioritas logo Jawa Timur
        $logoCell = $headerTable->addCell(1800, ['valign' => 'center']);
        $logoPath = public_path('assets/images/logo/logo_jatim.png'); // Logo Jawa Timur yang ada
        if (file_exists($logoPath) && filesize($logoPath) > 0) {
            try {
                $logoCell->addImage($logoPath, [
                    'width' => 75,
                    'height' => 90,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'wrappingStyle' => 'inline'
                ]);
            } catch (\Exception $e) {
                // Fallback jika gagal load gambar
                $logoCell->addText('LOGO\nJAWA TIMUR', ['bold' => true, 'size' => 10], ['alignment' => 'center']);
            }
        } else {
            $logoCell->addText('LOGO\nJAWA TIMUR', ['bold' => true, 'size' => 10], ['alignment' => 'center']);
        }

        // Kop surat cell
        $kopCell = $headerTable->addCell(8200, [
            'valign' => 'center',
            'borderBottomSize' => 18,
            'borderBottomColor' => '000000'
        ]);

        $kopCell->addText(
            'PEMERINTAH PROVINSI JAWA TIMUR',
            ['bold' => true, 'size' => 16, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 40]
        );
        $kopCell->addText(
            'SEKRETARIAT DAERAH',
            ['bold' => true, 'size' => 14, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 40]
        );
        $kopCell->addText(
            'Jl. Pahlawan No. 110, Surabaya 60176',
            ['size' => 11, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 20]
        );
        $kopCell->addText(
            'Telp. (031) 3524001 - 11, Pswt. 1467-1465-1489',
            ['size' => 11, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 20]
        );

        $section->addTextBreak(2);

        // Judul
        $section->addText(
            'TANDA PENERIMAAN SURAT',
            ['bold' => true, 'size' => 14, 'underline' => 'single', 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 300]
        );

        $section->addTextBreak(1);

        // Define table style untuk info
        $phpWord->addTableStyle('InfoTable', [
            'borderSize' => 0,
            'cellMargin' => 100,
        ]);

        // Tabel info surat
        $table = $section->addTable('InfoTable');

        $table->addRow();
        $table->addCell(3500)->addText('Telah Terima Surat dari', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(300)->addText(':', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(6000)->addText($data->dari ?? '-', ['size' => 12], ['spaceAfter' => 0]);

        $table->addRow();
        $table->addCell(3500)->addText('Tanggal', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(300)->addText(':', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(6000)->addText($data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-', ['size' => 12], ['spaceAfter' => 0]);

        $table->addRow();
        $table->addCell(3500)->addText('Nomor Surat', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(300)->addText(':', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(6000)->addText($data->nomor_surat ?? '-', ['size' => 12], ['spaceAfter' => 0]);

        $table->addRow();
        $table->addCell(3500)->addText('Perihal', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(300)->addText(':', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(6000)->addText($data->hal ?? '-', ['size' => 12], ['spaceAfter' => 0]);

        $table->addRow();
        $table->addCell(3500)->addText('Diterima', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(300)->addText(':', ['size' => 12], ['spaceAfter' => 0]);
        $table->addCell(6000)->addText($data->tgl_diterima ? date('d-m-Y', strtotime($data->tgl_diterima)) : date('d-m-Y'), ['size' => 12], ['spaceAfter' => 0]);

        $section->addTextBreak(4);

        // Tanda tangan di kanan bawah
        $footerTable = $section->addTable([
            'borderSize' => 0,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END,
        ]);
        $footerTable->addRow();
        $footerTable->addCell(4000); // Empty left cell
        $footerCell = $footerTable->addCell(5000);

        $footerCell->addText(
            'Surabaya, ' . ($data->tgl_diterima ? date('d F Y', strtotime($data->tgl_diterima)) : date('d F Y')),
            ['size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $footerCell->addText(
            'PENERIMA',
            ['size' => 12, 'bold' => true],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        $footerCell->addTextBreak(3);

        $footerCell->addText(
            $data->createdBy->fullname ?? 'PETUGAS ADMINISTRASI',
            ['bold' => true, 'underline' => 'single', 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $footerCell->addText(
            'NIP. ........................................',
            ['size' => 11],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        // Generate filename yang aman
        $fileName = 'Tanda_Terima_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Surat') . '_' . date('Y-m-d') . '.docx';

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export Surat Resmi ke Word dengan Logo Jawa Timur
     */
    public function exportSuratWord($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Set default font untuk look yang profesional
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Section dengan pengaturan halaman A4 yang tepat
        $section = $phpWord->addSection([
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(29.7),
            'orientation' => 'portrait',
        ]);

        // Define table styles untuk konsistensi
        $phpWord->addTableStyle('HeaderTable', [
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMargin' => 60,
            'borderSize' => 0,
        ]);

        $phpWord->addTableStyle('MainTable', [
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::START,
            'cellMargin' => 80,
            'borderSize' => 0,
        ]);

        // Header: Logo Jawa Timur + Kop Surat
        $headerTable = $section->addTable('HeaderTable');
        $headerTable->addRow();

        // Logo cell dengan logo Jawa Timur
        $logoCell = $headerTable->addCell(1800, ['valign' => 'center']);
        $logoPath = public_path('assets/images/logo/logo_jatim.png');
        if (file_exists($logoPath) && filesize($logoPath) > 0) {
            try {
                $logoCell->addImage($logoPath, [
                    'width' => 75,
                    'height' => 90,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'wrappingStyle' => 'inline'
                ]);
            } catch (\Exception $e) {
                $logoCell->addText('LOGO\nJAWA TIMUR', ['bold' => true, 'size' => 10], ['alignment' => 'center']);
            }
        } else {
            $logoCell->addText('LOGO\nJAWA TIMUR', ['bold' => true, 'size' => 10], ['alignment' => 'center']);
        }

        // Kop surat cell
        $kopCell = $headerTable->addCell(8200, [
            'valign' => 'center',
            'borderBottomSize' => 18,
            'borderBottomColor' => '000000',
        ]);

        $kopCell->addText(
            'PEMERINTAH PROVINSI JAWA TIMUR',
            ['bold' => true, 'size' => 16, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 40]
        );
        $kopCell->addText(
            'SEKRETARIAT DAERAH',
            ['bold' => true, 'size' => 14, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 40]
        );
        $kopCell->addText(
            'Jl. Pahlawan No. 110, Surabaya 60176',
            ['size' => 11, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 20]
        );
        $kopCell->addText(
            'Telp. (031) 3524001 - 11, Pswt. 1467-1465-1489',
            ['size' => 11, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 20]
        );

        $section->addTextBreak(2);

        // --- Bagian Informasi Surat dan Alamat Tujuan ---
        $mainTable = $section->addTable('MainTable');

        // Baris 1: Nomor Surat & Tanggal
        $mainTable->addRow();
        $leftCell1 = $mainTable->addCell(5000);
        $leftCell1->addText('Nomor', ['size' => 12], ['spaceAfter' => 0]);
        $leftCell1->addText(': ' . ($data->nomor_surat ?? '-'), ['size' => 12], ['spaceAfter' => 0]);

        $rightCell1 = $mainTable->addCell(5000);
        $rightCell1->addText(
            'Surabaya, ' . ($data->tgl_surat ? date('d F Y', strtotime($data->tgl_surat)) : date('d F Y')),
            ['size' => 12],
            ['alignment' => 'right', 'spaceAfter' => 0]
        );

        // Baris 2: Klasifikasi & Kepada
        $mainTable->addRow();
        $leftCell2 = $mainTable->addCell(5000);
        $leftCell2->addText('Klasifikasi', ['size' => 12], ['spaceAfter' => 0]);
        $leftCell2->addText(': ' . ($data->kode_klasifikasi ?? '-'), ['size' => 12], ['spaceAfter' => 0]);

        $rightCell2 = $mainTable->addCell(5000, ['valign' => 'top']);
        $rightCell2->addText('Kepada Yth.', ['size' => 12], ['alignment' => 'right', 'spaceAfter' => 0]);
        $rightCell2->addText($data->kepada ?? '-', ['size' => 12, 'bold' => true], ['alignment' => 'right', 'spaceAfter' => 0]);

        // Baris 3: Hal & di Tempat  
        $mainTable->addRow();
        $leftCell3 = $mainTable->addCell(5000);
        $leftCell3->addText('Hal', ['size' => 12], ['spaceAfter' => 0]);
        $leftCell3->addText(': ' . ($data->hal ?? '-'), ['size' => 12, 'bold' => true], ['spaceAfter' => 0]);

        $rightCell3 = $mainTable->addCell(5000, ['valign' => 'top']);
        $rightCell3->addText('di Tempat', ['size' => 12], ['alignment' => 'right', 'spaceAfter' => 0]);

        $section->addTextBreak(2);

        // Isi surat
        if (!empty($data->isi)) {
            $section->addText(
                $data->isi,
                ['size' => 12],
                ['alignment' => 'both', 'spaceAfter' => 300, 'lineHeight' => 1.5]
            );
        }

        $section->addTextBreak(3);

        // Footer dengan tembusan dan tanda tangan
        $footerTable = $section->addTable([
            'borderSize' => 0,
        ]);
        $footerTable->addRow();

        // Tembusan
        $tembusanCell = $footerTable->addCell(5000);
        if (!empty($data->tembusan)) {
            $tembusanCell->addText('Tembusan:', ['bold' => true, 'size' => 12], ['spaceAfter' => 100]);
            $items = preg_split('/\r\n|\r|\n|,|;/', (string) $data->tembusan);
            if (is_array($items)) {
                $counter = 1;
                foreach ($items as $item) {
                    $item = trim($item);
                    if ($item !== '') {
                        $tembusanCell->addText($counter . '. ' . $item, ['size' => 11], ['spaceAfter' => 80]);
                        $counter++;
                    }
                }
            }
        }

        // Tanda tangan
        $signCell = $footerTable->addCell(5000, ['valign' => 'top']);
        $signCell->addText(
            'a.n. GUBERNUR JAWA TIMUR',
            ['bold' => true, 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $signCell->addText(
            'SEKRETARIS DAERAH',
            ['bold' => true, 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        $signCell->addTextBreak(3);

        $signCell->addText(
            'Dr. H. HERU TJAHJONO, S.IP., M.Si.',
            ['bold' => true, 'underline' => 'single', 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $signCell->addText(
            'NIP. 19651015 199103 1 002',
            ['size' => 11],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        // Generate filename dan download
        $fileName = 'Surat_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Tanpa_Nomor') . '_' . date('Y-m-d') . '.docx';

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }



    /**
     * Export Lembar Disposisi ke Word dengan Logo Jawa Timur
     */
    public function exportSuratDisWord($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Set default font untuk look yang bersih
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        // Section dengan pengaturan halaman A4 yang tepat
        $section = $phpWord->addSection([
            'marginTop' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'marginLeft' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(3),
            'marginRight' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'marginBottom' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(2.5),
            'pageSizeW' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(21),
            'pageSizeH' => \PhpOffice\PhpWord\Shared\Converter::cmToTwip(29.7),
            'orientation' => 'portrait',
        ]);

        // Define table styles
        $phpWord->addTableStyle('HeaderTable', [
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER,
            'cellMargin' => 60,
            'borderSize' => 0,
        ]);

        $phpWord->addTableStyle('MainTable', [
            'cellMargin' => 80,
            'borderSize' => 8,
            'borderColor' => '000000',
        ]);

        // Header: Logo Jawa Timur + Kop Surat
        $headerTable = $section->addTable('HeaderTable');
        $headerTable->addRow();

        // Logo cell dengan logo Jawa Timur
        $logoCell = $headerTable->addCell(1800, ['valign' => 'center']);
        $logoPath = public_path('assets/images/logo/logo_jatim.png'); // Logo Jawa Timur
        if (file_exists($logoPath) && filesize($logoPath) > 0) {
            try {
                $logoCell->addImage($logoPath, [
                    'width' => 75,
                    'height' => 90,
                    'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
                    'wrappingStyle' => 'inline'
                ]);
            } catch (\Exception $e) {
                $logoCell->addText('LOGO\nJAWA TIMUR', ['bold' => true, 'size' => 10], ['alignment' => 'center']);
            }
        } else {
            $logoCell->addText('LOGO\nJAWA TIMUR', ['bold' => true, 'size' => 10], ['alignment' => 'center']);
        }

        // Kop surat cell
        $kopCell = $headerTable->addCell(8200, [
            'valign' => 'center',
            'borderBottomSize' => 18,
            'borderBottomColor' => '000000'
        ]);

        $kopCell->addText(
            'PEMERINTAH PROVINSI JAWA TIMUR',
            ['bold' => true, 'size' => 16, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 40]
        );
        $kopCell->addText(
            'SEKRETARIAT DAERAH',
            ['bold' => true, 'size' => 14, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 40]
        );
        $kopCell->addText(
            'Jl. Pahlawan No. 110, Surabaya 60176',
            ['size' => 11, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 20]
        );
        $kopCell->addText(
            'Telp. (031) 3524001 - 11, Pswt. 1467-1465-1489',
            ['size' => 11, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 20]
        );

        $section->addTextBreak(2);

        // Judul
        $section->addText(
            'LEMBAR DISPOSISI',
            ['bold' => true, 'size' => 16, 'color' => '000000'],
            ['alignment' => 'center', 'spaceAfter' => 300]
        );

        // Main Disposisi Table
        $table = $section->addTable('MainTable');

        // Header row dengan merge
        $table->addRow();
        $table->addCell(4000, ['gridSpan' => 2])->addText(
            'INFORMASI SURAT MASUK',
            ['bold' => true, 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $table->addCell(4000, ['gridSpan' => 2])->addText(
            'INFORMASI PENERIMAAN',
            ['bold' => true, 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        // First row: Surat dari & Klasifikasi
        $table->addRow();
        $table->addCell(2000)->addText('Surat dari', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->dari ?? '-'), ['size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText('Klasifikasi', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->kode_klasifikasi ?? '-'), ['size' => 11], ['spaceAfter' => 0]);

        // Second row: Tanggal surat & Diterima tanggal
        $table->addRow();
        $table->addCell(2000)->addText('Tanggal surat', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-'), ['size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText('Diterima tanggal', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->tgl_diterima ? date('d/m/Y', strtotime($data->tgl_diterima)) : date('d/m/Y')), ['size' => 11], ['spaceAfter' => 0]);

        // Third row: Nomor & Nomor Agenda
        $table->addRow();
        $table->addCell(2000)->addText('Nomor', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->nomor_surat ?? '-'), ['size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText('Nomor Agenda', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->noagenda ?? '-'), ['size' => 11], ['spaceAfter' => 0]);

        // Fourth row: Hal & Sifat
        $table->addRow();
        $table->addCell(2000)->addText('Hal', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->hal ?? '-'), ['size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText('Sifat', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->sifat ?? '-'), ['size' => 11], ['spaceAfter' => 0]);

        // Fifth row: Lampiran & Kosong  
        $table->addRow();
        $table->addCell(2000)->addText('Lampiran', ['bold' => false, 'size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText(': ' . ($data->jumlah_lampiran ?? '-'), ['size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText('', ['size' => 11], ['spaceAfter' => 0]);
        $table->addCell(2000)->addText('', ['size' => 11], ['spaceAfter' => 0]);

        $section->addTextBreak(1);

        // Header Diteruskan Kepada
        $section->addText(
            'DITERUSKAN KEPADA:',
            ['bold' => true, 'size' => 12],
            ['spaceAfter' => 100]
        );

        // Daftar penerima disposisi
        $daftarTable = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 100,
        ]);

        for ($i = 1; $i <= 8; $i++) {
            $daftarTable->addRow();
            $daftarTable->addCell(500)->addText($i . '.', ['size' => 11], ['spaceAfter' => 0]);
            $daftarTable->addCell(8500)->addText('', ['size' => 11], ['spaceAfter' => 0]);
        }

        $section->addTextBreak(1);

        // ISI DISPOSISI section
        $section->addText(
            'ISI DISPOSISI / INSTRUKSI:',
            ['bold' => true, 'size' => 12],
            ['spaceAfter' => 100]
        );

        // ISI DISPOSISI Table dengan tinggi yang cukup
        $isiDisposisiTable = $section->addTable([
            'borderSize' => 8,
            'borderColor' => '000000',
            'cellMargin' => 150,
        ]);

        $isiDisposisiTable->addRow(\PhpOffice\PhpWord\Shared\Converter::cmToTwip(6)); // 6cm height
        $isiCell = $isiDisposisiTable->addCell(10000);
        $isiCell->addText('', ['size' => 12], ['spaceAfter' => 0]);

        $section->addTextBreak(2);

        // Footer dengan tanda tangan
        $footerTable = $section->addTable([
            'borderSize' => 0,
            'alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::END,
        ]);
        $footerTable->addRow();
        $footerTable->addCell(4000); // Empty left cell
        $footerCell = $footerTable->addCell(5000);

        $footerCell->addText(
            'Surabaya, ' . date('d F Y'),
            ['size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $footerCell->addText(
            'a.n. GUBERNUR JAWA TIMUR',
            ['bold' => true, 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $footerCell->addText(
            'SEKRETARIS DAERAH',
            ['size' => 12, 'bold' => true],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        $footerCell->addTextBreak(3);

        $footerCell->addText(
            'Dr. H. HERU TJAHJONO, S.IP., M.Si.',
            ['bold' => true, 'underline' => 'single', 'size' => 12],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );
        $footerCell->addText(
            'NIP. 19651015 199103 1 002',
            ['size' => 11],
            ['alignment' => 'center', 'spaceAfter' => 0]
        );

        // Generate filename yang aman
        $fileName = 'Disposisi_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Tanpa_Nomor') . '_' . date('Y-m-d') . '.docx';

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
