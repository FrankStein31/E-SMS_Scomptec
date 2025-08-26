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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
     * Upload file scan untuk entri surat
     *
     * Method ini digunakan untuk menyimpan file scan yang diupload dalam format base64
     *
     * @param Request $request Request yang berisi images_input
     * @param int $entri_surat_id ID dari entri surat
     * @return \Illuminate\Http\RedirectResponse
     */
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
        $data = EntrySuratIsi::with(['FileScan', 'tujuanSurat.user', 'klasifikasi'])->find($id);

        if (!$data) {
            return redirect()->route('entrisurat.index')->with('error', 'Data tidak ditemukan');
        }

        // Jika klasifikasi tidak ter-load melalui relasi, coba cari manual
        if (!$data->klasifikasi && $data->kode_klasifikasi) {
            // Coba cari berdasarkan kode klasifikasi
            $klasifikasiByKode = MasterKlasifikasi::where('kodeklasifikasi', $data->kode_klasifikasi)->first();
            if ($klasifikasiByKode) {
                $data->klasifikasi = $klasifikasiByKode;
            } else {
                // Jika tidak ditemukan berdasarkan kode, coba cari berdasarkan ID (jika ULID)
                $klasifikasiById = MasterKlasifikasi::find($data->kode_klasifikasi);
                if ($klasifikasiById) {
                    $data->klasifikasi = $klasifikasiById;
                }
            }
        }

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
     * Export Tanda Terima Surat ke Excel sesuai template yang diberikan
     */
    public function exportTandaTerimaExcel($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set lebar kolom sesuai template
        $sheet->getColumnDimension('A')->setWidth(8);   // Logo area
        $sheet->getColumnDimension('B')->setWidth(35);  // Label field yang lebih lebar
        $sheet->getColumnDimension('C')->setWidth(3);   // Titik dua
        $sheet->getColumnDimension('D')->setWidth(40);  // Data area yang lebar
        $sheet->getColumnDimension('E')->setWidth(20);  // Area kosong/tanda tangan

        // Set margin untuk print A4
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setLeft(0.7);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setBottom(0.75);

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(16);
        $sheet->getRowDimension(4)->setRowHeight(16);

        // Header kop surat
        $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
        $sheet->mergeCells('B1:E1');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B2', 'SEKRETARIAT DAERAH');
        $sheet->mergeCells('B2:E2');
        $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B3', 'Jl. Pahlawan 110, Surabaya, Jawa Timur');
        $sheet->mergeCells('B3:E3');
        $sheet->getStyle('B3')->getFont()->setSize(10);
        $sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B4', 'Telp (031) 3524001 - 11, Pswt 1467-1465-1489');
        $sheet->mergeCells('B4:E4');
        $sheet->getStyle('B4')->getFont()->setSize(10);
        $sheet->getStyle('B4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Tambahkan logo Jawa Timur
        $logoPath = public_path('assets/images/logo/logo_jatim.png');
        if (file_exists($logoPath)) {
            try {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo Jawa Timur');
                $drawing->setDescription('Logo Pemprov Jatim');
                $drawing->setPath($logoPath);
                $drawing->setHeight(70);
                $drawing->setWidth(55);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(20);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);
            } catch (\Exception $e) {
                // Fallback text logo
                $sheet->setCellValue('A1', 'LOGO');
                $sheet->setCellValue('A2', 'JATIM');
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(8);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // Garis bawah header
        $sheet->getRowDimension(5)->setRowHeight(5);
        $sheet->getStyle('A5:E5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        // Spasi
        $sheet->getRowDimension(6)->setRowHeight(20);

        // Judul dokumen
        $sheet->setCellValue('A7', 'TANDA PENERIMAAN SURAT');
        $sheet->mergeCells('A7:E7');
        $sheet->getStyle('A7')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(7)->setRowHeight(25);

        // Spasi setelah judul
        $sheet->getRowDimension(8)->setRowHeight(20);

        // Data tanda terima sesuai template - mulai dari baris 9
        $row = 9;

        // Telah Terima Surat dari
        $sheet->setCellValue('A' . $row, 'Telah Terima Surat dari');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->dari ?? '-');
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Tanggal
        $sheet->setCellValue('A' . $row, 'Tanggal');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-');
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Nomor Surat
        $sheet->setCellValue('A' . $row, 'Nomor Surat');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->nomor_surat ?? '-');
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Perihal
        $sheet->setCellValue('A' . $row, 'Perihal');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->hal ?? '-');
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($row)->setRowHeight(22);
        $row++;

        // Diterima
        $sheet->setCellValue('A' . $row, 'Diterima');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->tgl_diterima ? date('d-m-Y', strtotime($data->tgl_diterima)) : date('d-m-Y'));
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getRowDimension($row)->setRowHeight(18);

        // Spasi sebelum tanda tangan
        $row += 6;

        // Area tanda tangan sesuai template - di sebelah kanan
        $sheet->setCellValue('D' . $row, 'SURABAYA, ' . ($data->tgl_diterima ? date('d/m/Y', strtotime($data->tgl_diterima)) : date('d/m/Y')));
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        $sheet->setCellValue('D' . $row, 'PENERIMA');
        $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Label "Operator" di sebelah kanan sesuai template
        $sheet->setCellValue('E' . $row, 'Operator');
        $sheet->getStyle('E' . $row)->getFont()->setSize(10);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(16);
        $row += 4;

        // Nama penandatangan dengan garis bawah
        $sheet->setCellValue('D' . $row, $data->createdBy->fullname ?? 'Operator');
        $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getRowDimension($row)->setRowHeight(20);
        $row++;

        // NIP
        $sheet->setCellValue('D' . $row, 'NIP');
        $sheet->getStyle('D' . $row)->getFont()->setSize(10);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(16);

        // Set print area dan orientation
        $sheet->getPageSetup()->setPrintArea('A1:E' . ($row + 2));
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Generate filename
        $fileName = 'Tanda_Terima_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Surat') . '_' . date('Y-m-d') . '.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export Surat ke Excel dengan format yang konsisten
     */
    public function exportSuratExcel($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set lebar kolom sesuai template tanda terima
        $sheet->getColumnDimension('A')->setWidth(8);   // Logo area
        $sheet->getColumnDimension('B')->setWidth(35);  // Label field
        $sheet->getColumnDimension('C')->setWidth(3);   // Titik dua
        $sheet->getColumnDimension('D')->setWidth(40);  // Data area
        $sheet->getColumnDimension('E')->setWidth(20);  // Info tambahan

        // Set margin untuk print
        $sheet->getPageMargins()->setTop(0.75);
        $sheet->getPageMargins()->setLeft(0.7);
        $sheet->getPageMargins()->setRight(0.7);
        $sheet->getPageMargins()->setBottom(0.75);

        // Set tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(20);
        $sheet->getRowDimension(2)->setRowHeight(18);
        $sheet->getRowDimension(3)->setRowHeight(16);
        $sheet->getRowDimension(4)->setRowHeight(16);

        // Header kop surat yang sama dengan tanda terima
        $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
        $sheet->mergeCells('B1:E1');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B2', 'SEKRETARIAT DAERAH');
        $sheet->mergeCells('B2:E2');
        $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B3', 'Jl. Pahlawan 110, Surabaya, Jawa Timur');
        $sheet->mergeCells('B3:E3');
        $sheet->getStyle('B3')->getFont()->setSize(10);
        $sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B4', 'Telp (031) 3524001 - 11, Pswt 1467-1465-1489');
        $sheet->mergeCells('B4:E4');
        $sheet->getStyle('B4')->getFont()->setSize(10);
        $sheet->getStyle('B4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Tambahkan logo Jawa Timur
        $logoPath = public_path('assets/images/logo/logo_jatim.png');
        if (file_exists($logoPath)) {
            try {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo Jawa Timur');
                $drawing->setDescription('Logo Pemprov Jatim');
                $drawing->setPath($logoPath);
                $drawing->setHeight(70);
                $drawing->setWidth(55);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(20);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);
            } catch (\Exception $e) {
                // Fallback text logo
                $sheet->setCellValue('A1', 'LOGO');
                $sheet->setCellValue('A2', 'JATIM');
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(8);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // Garis bawah header
        $sheet->getRowDimension(5)->setRowHeight(5);
        $sheet->getStyle('A5:E5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        // Spasi setelah header
        $sheet->getRowDimension(6)->setRowHeight(20);

        // Info surat dalam format yang konsisten
        $row = 7;

        // Nomor surat
        $sheet->setCellValue('A' . $row, 'Nomor');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->nomor_surat ?? '-');
        $sheet->setCellValue('E' . $row, 'Surabaya, ' . ($data->tgl_surat ? date('d F Y', strtotime($data->tgl_surat)) : date('d F Y')));
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getStyle('E' . $row)->getFont()->setSize(11);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Klasifikasi
        $sheet->setCellValue('A' . $row, 'Klasifikasi');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->kode_klasifikasi ?? '-');
        $sheet->setCellValue('E' . $row, 'Kepada Yth.');
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getStyle('E' . $row)->getFont()->setSize(11)->setBold(true);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        // Hal
        $sheet->setCellValue('A' . $row, 'Hal');
        $sheet->setCellValue('C' . $row, ':');
        $sheet->setCellValue('D' . $row, $data->hal ?? '-');
        $sheet->setCellValue('E' . $row, $data->kepada ?? '-');
        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getFont()->setSize(11)->setBold(true);
        $sheet->getStyle('E' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setWrapText(true);
        $sheet->getRowDimension($row)->setRowHeight(22);
        $row++;

        // Di tempat
        $sheet->setCellValue('E' . $row, 'di Tempat');
        $sheet->getStyle('E' . $row)->getFont()->setSize(11);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row += 2;

        // Isi surat
        if (!empty($data->isi)) {
            $sheet->setCellValue('A' . $row, 'Isi Surat:');
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(11);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;

            $sheet->setCellValue('A' . $row, $data->isi);
            $sheet->mergeCells('A' . $row . ':E' . $row);
            $sheet->getStyle('A' . $row)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A' . $row)->getFont()->setSize(11);
            $sheet->getRowDimension($row)->setRowHeight(35);
            $row += 2;
        }

        // Tembusan
        if (!empty($data->tembusan)) {
            $sheet->setCellValue('A' . $row, 'Tembusan:');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(11);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;

            $items = preg_split('/\r\n|\r|\n|,|;/', (string) $data->tembusan);
            if (is_array($items)) {
                $counter = 1;
                foreach ($items as $item) {
                    $item = trim($item);
                    if ($item !== '') {
                        $sheet->setCellValue('A' . $row, $counter . '. ' . $item);
                        $sheet->getStyle('A' . $row)->getFont()->setSize(11);
                        $sheet->getRowDimension($row)->setRowHeight(18);
                        $row++;
                        $counter++;
                    }
                }
            }
            $row++;
        }

        // Tanda tangan - format konsisten dengan tanda terima
        $row += 2;
        $sheet->setCellValue('D' . $row, 'a.n. GUBERNUR JAWA TIMUR');
        $sheet->getStyle('D' . $row)->getFont()->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row++;

        $sheet->setCellValue('D' . $row, 'SEKRETARIS DAERAH');
        $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(18);
        $row += 4;

        $sheet->setCellValue('D' . $row, 'Dr. H. HERU TJAHJONO, S.IP., M.Si.');
        $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getRowDimension($row)->setRowHeight(20);
        $row++;

        $sheet->setCellValue('D' . $row, 'NIP. 19651015 199103 1 002');
        $sheet->getStyle('D' . $row)->getFont()->setSize(10);
        $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(16);

        // Set print area dan orientation
        $sheet->getPageSetup()->setPrintArea('A1:E' . ($row + 2));
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Generate filename
        $fileName = 'Surat_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Tanpa_Nomor') . '_' . date('Y-m-d') . '.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export Lembar Disposisi ke Excel dengan layout yang lebih compact dan rapi untuk cetak
     */
    public function exportSuratDisExcel($id)
    {
        $data = EntrySuratIsi::with(['createdBy'])->findOrFail($id);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set lebar kolom yang lebih compact untuk cetak
        $sheet->getColumnDimension('A')->setWidth(6);   // Logo area
        $sheet->getColumnDimension('B')->setWidth(16);  // Label kiri
        $sheet->getColumnDimension('C')->setWidth(18);  // Data kiri
        $sheet->getColumnDimension('D')->setWidth(16);  // Label kanan
        $sheet->getColumnDimension('E')->setWidth(18);  // Data kanan
        $sheet->getColumnDimension('F')->setWidth(18);  // Tanda tangan

        // Set margin untuk print yang lebih ketat
        $sheet->getPageMargins()->setTop(0.5);
        $sheet->getPageMargins()->setLeft(0.5);
        $sheet->getPageMargins()->setRight(0.5);
        $sheet->getPageMargins()->setBottom(0.5);

        // Set tinggi baris header yang lebih compact
        $sheet->getRowDimension(1)->setRowHeight(18);
        $sheet->getRowDimension(2)->setRowHeight(16);
        $sheet->getRowDimension(3)->setRowHeight(14);
        $sheet->getRowDimension(4)->setRowHeight(14);

        // Header kop surat
        $sheet->setCellValue('B1', 'PEMERINTAH PROVINSI JAWA TIMUR');
        $sheet->mergeCells('B1:F1');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B2', 'SEKRETARIAT DAERAH');
        $sheet->mergeCells('B2:F2');
        $sheet->getStyle('B2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B3', 'Jl. Pahlawan No. 110, Surabaya 60176');
        $sheet->mergeCells('B3:F3');
        $sheet->getStyle('B3')->getFont()->setSize(9);
        $sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('B4', 'Telp. (031) 3524001 - 11, Pswt 1467-1465-1489');
        $sheet->mergeCells('B4:F4');
        $sheet->getStyle('B4')->getFont()->setSize(9);
        $sheet->getStyle('B4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Tambahkan logo Jawa Timur yang lebih kecil
        $logoPath = public_path('assets/images/logo/logo_jatim.png');
        if (file_exists($logoPath)) {
            try {
                $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                $drawing->setName('Logo Jawa Timur');
                $drawing->setDescription('Logo Pemprov Jatim');
                $drawing->setPath($logoPath);
                $drawing->setHeight(60);
                $drawing->setWidth(48);
                $drawing->setCoordinates('A1');
                $drawing->setOffsetX(15);
                $drawing->setOffsetY(5);
                $drawing->setWorksheet($sheet);
            } catch (\Exception $e) {
                // Fallback text logo
                $sheet->setCellValue('A1', 'LOGO');
                $sheet->setCellValue('A2', 'JATIM');
                $sheet->getStyle('A1:A2')->getFont()->setBold(true)->setSize(7);
                $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        }

        // Garis bawah header
        $sheet->getRowDimension(5)->setRowHeight(4);
        $sheet->getStyle('A5:F5')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK);

        // Judul
        $sheet->setCellValue('A6', 'LEMBAR DISPOSISI');
        $sheet->mergeCells('A6:F6');
        $sheet->getStyle('A6')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(6)->setRowHeight(20);

        // Spasi minimal
        $sheet->getRowDimension(7)->setRowHeight(8);

        // Border style untuk tabel
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Tabel informasi surat dalam 2 kolom
        $row = 8;

        // Header tabel
        $sheet->setCellValue('A' . $row, 'INFORMASI SURAT MASUK');
        $sheet->setCellValue('D' . $row, 'INFORMASI PENERIMAAN');
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->mergeCells('D' . $row . ':F' . $row);
        $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($borderStyle);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('A' . $row . ':F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('E8E8E8');
        $sheet->getRowDimension($row)->setRowHeight(16);
        $row++;

        // Data baris dengan tinggi yang compact
        $dataRows = [
            ['Surat dari', $data->dari ?? '-', 'Klasifikasi', $data->kode_klasifikasi ?? '-'],
            ['Tanggal surat', $data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-', 'Tanggal diterima    x', $data->tgl_diterima ? date('d/m/Y', strtotime($data->tgl_diterima)) : date('d/m/Y')],
            ['Nomor', $data->nomor_surat ?? '-', 'Nomor Agenda', $data->noagenda ?? '-'],
            ['Hal', $data->hal ?? '-', 'Sifat', $data->sifat ?? '-'],
            ['Lampiran', $data->jumlah_lampiran ?? '0', '', '']
        ];

        foreach ($dataRows as $dataRow) {
            $sheet->setCellValue('A' . $row, $dataRow[0]);
            $sheet->setCellValue('C' . $row, $dataRow[1]);
            $sheet->setCellValue('D' . $row, $dataRow[2]);
            $sheet->setCellValue('F' . $row, $dataRow[3]);
            $sheet->getStyle('A' . $row . ':F' . $row)->applyFromArray($borderStyle);
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(9);
            $sheet->getStyle('C' . $row)->getFont()->setSize(9);
            $sheet->getStyle('D' . $row)->getFont()->setBold(true)->setSize(9);
            $sheet->getStyle('F' . $row)->getFont()->setSize(9);

            // Set alignment untuk data agar sejajar
            $sheet->getStyle('C' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('F' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Wrap text untuk "Hal" yang mungkin panjang
            if ($dataRow[0] == 'Hal') {
                $sheet->getStyle('C' . $row)->getAlignment()->setWrapText(true);
                $sheet->getRowDimension($row)->setRowHeight(18);
            } else {
                $sheet->getRowDimension($row)->setRowHeight(14);
            }
            $row++;
        }

        $row++; // Spasi

        // Diteruskan kepada - lebih compact
        $sheet->setCellValue('A' . $row, 'DITERUSKAN KEPADA:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(10);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row++;

        // List penerima (6 baris untuk menghemat ruang)
        for ($i = 1; $i <= 6; $i++) {
            $sheet->setCellValue('A' . $row, $i . '.');
            $sheet->setCellValue('B' . $row, '');
            $sheet->mergeCells('B' . $row . ':F' . $row);
            $sheet->getStyle('A' . $row . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A' . $row)->getFont()->setSize(9);
            $sheet->getRowDimension($row)->setRowHeight(16);
            $row++;
        }

        $row++; // Spasi minimal

        // ISI DISPOSISI
        $sheet->setCellValue('A' . $row, 'ISI DISPOSISI / INSTRUKSI:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(10);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row++;

        // Area untuk isi disposisi (4 baris untuk menghemat ruang)
        for ($i = 0; $i < 4; $i++) {
            $sheet->setCellValue('A' . $row, '');
            $sheet->mergeCells('A' . $row . ':F' . $row);
            $sheet->getStyle('A' . $row . ':F' . $row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getRowDimension($row)->setRowHeight(20);
            $row++;
        }

        // Tanda tangan - compact
        $row += 2;
        $sheet->setCellValue('E' . $row, 'Surabaya, ' . date('d F Y'));
        $sheet->getStyle('E' . $row)->getFont()->setSize(10);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row++;

        $sheet->setCellValue('E' . $row, 'a.n. GUBERNUR JAWA TIMUR');
        $sheet->getStyle('E' . $row)->getFont()->setSize(10);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row++;

        $sheet->setCellValue('E' . $row, 'SEKRETARIS DAERAH');
        $sheet->getStyle('E' . $row)->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(14);
        $row += 3;

        $sheet->setCellValue('E' . $row, 'Dr. H. HERU TJAHJONO, S.IP., M.Si.');
        $sheet->getStyle('E' . $row)->getFont()->setBold(true)->setSize(10);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E' . $row)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getRowDimension($row)->setRowHeight(16);
        $row++;

        $sheet->setCellValue('E' . $row, 'NIP. 19651015 199103 1 002');
        $sheet->getStyle('E' . $row)->getFont()->setSize(9);
        $sheet->getStyle('E' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension($row)->setRowHeight(12);

        // Set print area yang tepat dan orientation
        $sheet->getPageSetup()->setPrintArea('A1:F' . ($row + 1));
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Set scale untuk memastikan muat dalam 1 halaman
        $sheet->getPageSetup()->setScale(85);

        // Set print options
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getPageSetup()->setFitToWidth(1);
        $sheet->getPageSetup()->setFitToHeight(1);

        // Generate filename
        $fileName = 'Disposisi_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Tanpa_Nomor') . '_' . date('Y-m-d') . '.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }
}
