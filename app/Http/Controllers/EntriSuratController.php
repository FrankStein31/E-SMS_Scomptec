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
use Illuminate\Support\Facades\Log;
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

        // Cari template dengan ekstensi .xls atau .xlsx
        $candidates = [
            public_path('doc_cetak' . DIRECTORY_SEPARATOR . 'Tanda Terima.xls'),
            public_path('doc_cetak' . DIRECTORY_SEPARATOR . 'Tanda Terima.xlsx'),
        ];
        $templatePath = null;
        foreach ($candidates as $candidate) {
            if (file_exists($candidate)) {
                $templatePath = $candidate;
                break;
            }
        }
        if (!$templatePath) {
            return redirect()->back()->with('danger', 'Template Excel tidak ditemukan di: public/doc_cetak (Tanda Terima.xls atau Tanda Terima.xlsx)');
        }

        // Load template agar format/lay out terjaga
        try {
            $detectedType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($templatePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Gagal mengenali tipe template: ' . $e->getMessage());
        }

        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath); // auto-detect
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Gagal memuat template: ' . $e->getMessage());
        }
        $sheet = $spreadsheet->getActiveSheet();

        // Siapkan nilai yang akan diinject ke template (gunakan placeholder di template)
        $replacements = [
            '{DARI}' => (string)($data->dari ?? '-'),
            '{TANGGAL_SURAT_DMY}' => $data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-',
            '{NOMOR_SURAT}' => (string)($data->nomor_surat ?? '-'),
            '{PERIHAL}' => (string)($data->hal ?? '-'),
            '{DITERIMA_TANGGAL_DMY}' => $data->tgl_diterima ? date('d-m-Y', strtotime($data->tgl_diterima)) : date('d-m-Y'),
            '{DITERIMA_JAM}' => $data->created_at ? date('H:i:s', strtotime($data->created_at)) : date('H:i:s'),
            '{SURABAYA_TANGGAL_DMY}' => $data->tgl_diterima ? date('d/m/Y', strtotime($data->tgl_diterima)) : date('d/m/Y'),
            '{PENERIMA_NAMA}' => (string)($data->createdBy->fullname ?? 'Operator'),
            '{PENERIMA_NIP}' => '',
        ];

        // Ganti placeholder pada seluruh sel string dalam sheet aktif
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
        for ($row = 1; $row <= $highestRow; $row++) {
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
                $cell = $sheet->getCell($colLetter . $row);
                $value = $cell->getValue();
                if (is_string($value) && $value !== '') {
                    $newValue = strtr($value, $replacements);
                    if ($newValue !== $value) {
                        $cell->setValueExplicit($newValue, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    }
                }
            }
        }

        // Nama file keluaran mengikuti tipe template (xls jika template xls, selain itu xlsx)
        $writerType = ($detectedType === 'Xls') ? 'Xls' : 'Xlsx';
        $ext = strtolower($writerType) === 'xls' ? 'xls' : 'xlsx';
        $fileName = 'Tanda_Terima_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $data->hal ?? 'Surat') . '_' . date('Y-m-d') . '.' . $ext;

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, $writerType);
        $tempFile = tempnam(sys_get_temp_dir(), 'tt_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    /**
     * Export Surat ke Excel
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
                $drawing->setOffsetX(65); // Dipindah ke kanan sesuai dengan exportTandaTerimaExcel
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
     * Export Lembar Disposisi ke Excel
     */
    public function exportSuratDisExcel($id)
    {
        $data = EntrySuratIsi::with(['createdBy', 'disposisis'])->findOrFail($id);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set lebar kolom untuk layout yang optimal
        $sheet->getColumnDimension('A')->setWidth(18);  // Label kiri
        $sheet->getColumnDimension('B')->setWidth(25);  // Data kiri
        $sheet->getColumnDimension('C')->setWidth(18);  // Label kanan
        $sheet->getColumnDimension('D')->setWidth(25);  // Data kanan

        // Set margin untuk print yang optimal
        $sheet->getPageMargins()->setTop(0.8);
        $sheet->getPageMargins()->setLeft(0.8);
        $sheet->getPageMargins()->setRight(0.8);
        $sheet->getPageMargins()->setBottom(0.8);

        // Header kop surat
        $sheet->setCellValue('A1', 'PEMERINTAH PROVINSI JAWA TIMUR');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(22);

        $sheet->setCellValue('A2', 'SEKRETARIAT DAERAH');
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(2)->setRowHeight(20);

        // Spasi
        $sheet->getRowDimension(3)->setRowHeight(15);

        // Judul dokumen
        $sheet->setCellValue('A4', 'LEMBAR DISPOSISI');
        $sheet->mergeCells('A4:D4');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(25);

        // Info klasifikasi dan tanggal di kanan
        $sheet->setCellValue('C5', 'Klasifikasi : ' . ($data->kode_klasifikasi ?? '000.231'));
        $sheet->setCellValue('C6', 'Diterima tanggal : ' . ($data->tgl_diterima ? date('d-m-Y', strtotime($data->tgl_diterima)) : date('d-m-Y')));
        $sheet->mergeCells('C5:D5');
        $sheet->mergeCells('C6:D6');
        $sheet->getStyle('C5:C6')->getFont()->setSize(11);
        $sheet->getRowDimension(5)->setRowHeight(18);
        $sheet->getRowDimension(6)->setRowHeight(18);

        // Spasi
        $sheet->getRowDimension(7)->setRowHeight(15);

        // Border style untuk tabel yang rapi
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];

        // Tabel informasi surat - format 2 kolom yang rapi
        $row = 8;

        // Data surat dalam format tabel yang konsisten
        $dataRows = [
            ['Surat dari', $data->dari ?? '-'],
            ['Nomor Agenda', $data->noagenda ?? '-'],
            ['Tanggal surat', $data->tgl_surat ? date('d/m/Y', strtotime($data->tgl_surat)) : '-', '', ''],
            ['Nomor surat', $data->nomor_surat ?? '-', '', ''],
            ['Perihal', $data->hal ?? '-', '', '']
        ];

        foreach ($dataRows as $index => $dataRow) {
            // Apply border untuk semua sel dalam baris
            $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($borderStyle);

            $sheet->setCellValue('A' . $row, $dataRow[0]);
            $sheet->setCellValue('B' . $row, $dataRow[1]);

            // Hanya tampilkan kolom kanan jika ada data
            if (!empty($dataRow[2])) {
                $sheet->setCellValue('C' . $row, $dataRow[2]);
                $sheet->setCellValue('D' . $row, $dataRow[3]);
            }

            // Style untuk label (kolom A dan C)
            $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(11);
            $sheet->getStyle('C' . $row)->getFont()->setBold(true)->setSize(11);

            // Style untuk data (kolom B dan D)
            $sheet->getStyle('B' . $row)->getFont()->setSize(11);
            $sheet->getStyle('D' . $row)->getFont()->setSize(11);

            // Set alignment left untuk data agar rapi
            $sheet->getStyle('B' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle('D' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            // Set tinggi baris sesuai konten
            if ($dataRow[0] == 'Perihal' && strlen($dataRow[1]) > 30) {
                $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);
                $sheet->getRowDimension($row)->setRowHeight(30);
            } else {
                $sheet->getRowDimension($row)->setRowHeight(22);
            }
            $row++;
        }

        $row += 2; // Spasi

        // Diteruskan kepada dengan border yang konsisten
        $sheet->setCellValue('A' . $row, 'Diteruskan kepada:');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet->getRowDimension($row)->setRowHeight(20);
        $row++;

        // Ambil data disposisi untuk mengisi "Diteruskan kepada"
        // Debug: Log untuk melihat apakah ada data disposisi
        Log::info('Checking disposisi data for entry surat ID: ' . $id);

        $disposisiList = \App\Models\DisposisiBaru::where('entrysurat_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        Log::info('Found ' . $disposisiList->count() . ' disposisi records');

        // Jika tidak ada data disposisi, coba ambil dari EntrySuratTujuan
        $kepadaEntries = [];

        if ($disposisiList->count() > 0) {
            // Gunakan data dari disposisi
            foreach ($disposisiList as $disposisi) {
                Log::info('Processing disposisi kepada: ' . $disposisi->kepada);

                $kepadaArr = array_filter(array_unique(explode(',', $disposisi->kepada)));
                foreach ($kepadaArr as $userId) {
                    $userId = trim($userId);
                    if (empty($userId)) continue;

                    $user = \App\Models\User::find($userId);
                    if ($user) {
                        // Tampilkan hanya nama satker jika tersedia, fallback ke nama user
                        $satkerName = null;
                        if ($user->masterSatker && !empty($user->masterSatker->satker)) {
                            $satkerName = $user->masterSatker->satker;
                        } elseif ($user->satker && !empty($user->satker->satker)) {
                            $satkerName = $user->satker->satker;
                        }
                        $entryText = $satkerName ?: $user->fullname;
                        $kepadaEntries[] = $entryText;

                        Log::info('Added user: ' . $user->fullname . ' with satker: ' . ($user->masterSatker ? $user->masterSatker->satker : 'none'));
                    } else {
                        // Jika bukan ID numerik, anggap ini label/nama satker yang sudah siap tampil
                        if (!ctype_digit($userId)) {
                            $kepadaEntries[] = $userId;
                        }
                        Log::warning('User not found for ID: ' . $userId);
                    }
                }
            }
        } else {
            // Fallback: gunakan data dari EntrySuratTujuan atau kepada field
            Log::info('No disposisi found, trying fallback methods');

            // Coba dari tujuan surat
            $tujuanList = \App\Models\EntrySuratTujuan::where('entrysurat_id', $id)->get();
            if ($tujuanList->count() > 0) {
                Log::info('Found ' . $tujuanList->count() . ' tujuan records');
                foreach ($tujuanList as $tujuan) {
                    $user = \App\Models\User::find($tujuan->userid_tujuan);
                    if ($user) {
                        // Tampilkan hanya nama satker jika tersedia, fallback ke nama user
                        $satkerName = null;
                        if ($user->masterSatker && !empty($user->masterSatker->satker)) {
                            $satkerName = $user->masterSatker->satker;
                        } elseif ($user->satker && !empty($user->satker->satker)) {
                            $satkerName = $user->satker->satker;
                        }
                        $entryText = $satkerName ?: $user->fullname;
                        $kepadaEntries[] = $entryText;
                    }
                }
            } else {
                // Terakhir, coba dari field kepada di entry surat
                if (!empty($data->kepada)) {
                    Log::info('Trying kepada field: ' . $data->kepada);
                    // Asumsi kepada berisi nama-nama yang dipisah koma
                    $kepadaNames = array_filter(array_map('trim', explode(',', $data->kepada)));
                    foreach ($kepadaNames as $name) {
                        $kepadaEntries[] = $name;
                    }
                }
            }
        }

        // Filter duplikat dan kosong
        $kepadaEntries = array_values(array_filter(array_unique($kepadaEntries)));
        Log::info('Final kepada entries count: ' . count($kepadaEntries));

        // Jika masih tidak ada data, tampilkan minimal 5 baris kosong
        if (empty($kepadaEntries)) {
            $kepadaEntries = array_fill(0, 5, '');
        } else {
            // Pastikan minimal 5 baris, tambah baris kosong jika perlu
            while (count($kepadaEntries) < 5) {
                $kepadaEntries[] = '';
            }
        }

        // Tampilkan maksimal 5 penerima
        for ($i = 0; $i < 5; $i++) {
            $sheet->setCellValue('A' . $row, ($i + 1) . '.');
            $sheet->setCellValue('B' . $row, $kepadaEntries[$i] ?? '');
            $sheet->mergeCells('B' . $row . ':D' . $row);

            // Apply border untuk semua sel
            $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($borderStyle);

            $sheet->getStyle('A' . $row)->getFont()->setSize(11);
            $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);

            // Style untuk data kepada
            $sheet->getStyle('B' . $row)->getFont()->setSize(11);
            $sheet->getStyle('B' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            $sheet->getStyle('B' . $row)->getAlignment()->setWrapText(true);

            // Set tinggi baris berdasarkan konten
            if (!empty($kepadaEntries[$i]) && strpos($kepadaEntries[$i], "\n") !== false) {
                $sheet->getRowDimension($row)->setRowHeight(45); // Tinggi untuk 3 baris teks
            } else {
                $sheet->getRowDimension($row)->setRowHeight(28);
            }
            $row++;
        }

        $row += 2; // Spasi

        // ISI DISPOSISI dengan header yang rapi
        $sheet->setCellValue('A' . $row, 'ISI DISPOSISI');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($borderStyle);
        $sheet->getRowDimension($row)->setRowHeight(25);
        $row++;

        // Area kosong untuk isi disposisi dengan border yang konsisten (12 baris)
        for ($i = 0; $i < 12; $i++) {
            $sheet->setCellValue('A' . $row, '');
            $sheet->mergeCells('A' . $row . ':D' . $row);
            $sheet->getStyle('A' . $row . ':D' . $row)->applyFromArray($borderStyle);
            $sheet->getRowDimension($row)->setRowHeight(30);
            $row++;
        }

        // Set print area dan orientation yang optimal
        $sheet->getPageSetup()->setPrintArea('A1:D' . ($row));
        $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
        $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

        // Set scale untuk memastikan tampilan optimal
        $sheet->getPageSetup()->setScale(90);

        // Set print options untuk hasil terbaik
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
