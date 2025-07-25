<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisSurat;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\DataTables\AktivitasDataTable;

class ReportSuratController extends Controller
{
    /**
     * undocumented function summary
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function surat(Request $request, \App\DataTables\ReportSuratDataTable $dataTable)
    {
        $jenisSurat = MasterJenisSurat::all();
        $satker = MasterSatker::all();
        if ($request->ajax()) {
            return $dataTable->ajax();
        }
        return $dataTable->render('report.surat', compact('jenisSurat', 'satker'));
    }

    /**
     * statistik
     *
     * Undocumented function long description
     *
     * @param Type $var Description
     * @return type
     * @throws conditon
     **/
    public function statistik(Request $request)
    {
        $tahun = $request->input('tahun', date('Y'));
        $jenis = $request->input('jenis_surat', null);
        $data = self::getStatistik(Auth::user()->id, $jenis, $tahun);
        $jenisSurat = MasterJenisSurat::all();

        return view('report.statistik', compact(
            "data",
            "jenisSurat",
            "tahun",
            "jenis"
        ));
    }

    public static function getStatistik($userid, $jenisid, $tahun)
    {
        $user = DB::table('users')
            ->select('satkerid', 'usergroupid')
            ->where('id', $userid)
            ->first();

        $v_satkerid = $user->usergroupid == 4 ? '%' : $user->satkerid;
        $v_jenisid = $jenisid == '00' ? '%' : $jenisid;
        $v_tahun = $tahun == '0' ? '%' : $tahun;

        // Ambil statistik bulanan dari entry_surat_isis
        $stat_masuk = DB::table('entry_surat_isis')
            ->selectRaw('MONTH(tgl_diarahkan) as bulan, COUNT(*) as jumlah')
            ->when($tahun, function ($q) use ($tahun) {
                $q->whereYear('tgl_diarahkan', $tahun);
            })
            ->when($jenisid, function ($q) use ($jenisid) {
                if ($jenisid && $jenisid != '00') $q->where('jenis_id', $jenisid);
            })
            ->groupBy(DB::raw('MONTH(tgl_diarahkan)'))
            ->pluck('jumlah', 'bulan');

        // Ambil statistik bulanan dari surat_keluar_isi
        $stat_keluar = DB::table('surat_keluar_isis')
            ->selectRaw('MONTH(tgl_surat) as bulan, COUNT(*) as jumlah')
            ->when($tahun, function ($q) use ($tahun) {
                $q->whereYear('tgl_surat', $tahun);
            })
            ->when($jenisid, function ($q) use ($jenisid) {
                if ($jenisid && $jenisid != '00') $q->where('jenis_id', $jenisid);
            })
            ->groupBy(DB::raw('MONTH(tgl_surat)'))
            ->pluck('jumlah', 'bulan');

        // Gabungkan hasil ke array bulan
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $nama_bulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ][$i];
            $result[] = [
                'bulan' => $i,
                'nama_bulan' => $nama_bulan,
                'jumlah_masuk' => $stat_masuk[$i] ?? 0,
                'jumlah_keluar' => $stat_keluar[$i] ?? 0,
            ];
        }
        return $result;
    }

    public function getStatistik2($userid, $jenisid, $tahun)
    {
        // Ambil satkerid dan usergroupid
        $user = DB::table('users')
            ->select('satkerid', 'usergroupid')
            ->where('id', $userid)
            ->first();

        $satkerid = $user->usergroupid == 4 ? '%' : $user->satkerid;
        $jenisid = $jenisid == '00' ? '%' : $jenisid;
        $tahun = $tahun == '0' ? '%' : $tahun;

        // Buat array bulan
        $bulanArray = range(1, 12);

        // Build subqueries seperti dalam stored procedure
        $query1 = DB::table('entry_surat_isis as b')
            ->selectRaw("DISTINCT CASE WHEN b.jenis_id = 0 THEN -1 ELSE b.jenis_id END as jenis_id,
                         b.id as surat_id,
                         MONTH(b.tgl_diarahkan) as bulan,
                         YEAR(b.tgl_diarahkan) as tahun")
            ->leftJoin('entry_surat_tujuans as a', 'b.id', '=', 'a.entrysurat_id')
            ->where(function ($q) use ($satkerid) {
                $q->where('a.satkerid_tujuan', 'like', $satkerid . '%')
                    ->orWhere('b.satkerid_pembuat', 'like', $satkerid . '%');
            });

        // dd($query1->get());

        $query2 = DB::table('disposisi_isi as b')
            ->selectRaw("DISTINCT 0 as jenis_id,
                         b.disposisi_id as surat_id,
                         MONTH(b.tgl_disposisi) as bulan,
                         YEAR(b.tgl_disposisi) as tahun")
            ->leftJoin('disposisi_tujuan as a', 'b.disposisi_id', '=', 'a.disposisi_id')
            ->where(function ($q) use ($satkerid) {
                $q->where('a.satkerid_tujuan', 'like', $satkerid . '%')
                    ->orWhere('b.satkerid_pembuat', 'like', $satkerid . '%');
            });

        $query3 = DB::table('suratkeluar_riwayat as z')
            ->selectRaw("DISTINCT a.jenis_id,
                         a.suratkeluar_id as surat_id,
                         MONTH(z.tgl_update) as bulan,
                         YEAR(z.tgl_update) as tahun")
            ->leftJoin('suratkeluar_isi as a', function ($join) {
                $join->on('z.suratkeluar_id', '=', 'a.suratkeluar_id')
                    ->on('z.revisi_id', '=', 'a.revisi_id');
            })
            ->where(function ($q) use ($satkerid) {
                $q->where('z.satkerid_pembuat', 'like', $satkerid . '%')
                    ->orWhere(function ($q2) use ($satkerid) {
                        $q2->where('z.satkerid_tujuan', 'like', $satkerid . '%')
                            ->where('z.last_sent', '=', 1);
                    })
                    ->orWhere('z.satkerid_final', 'like', $satkerid . '%');
            })
            ->where('z.status', '>', 1)
            ->whereRaw("z.nourut_riw = (
                SELECT MAX(nourut_riw) 
                FROM suratkeluar_riwayat 
                WHERE suratkeluar_id = z.suratkeluar_id 
                AND (satkerid_pembuat LIKE '{$satkerid}%' 
                     OR (satkerid_tujuan LIKE '{$satkerid}%' AND last_sent = 1) 
                     OR satkerid_final LIKE '{$satkerid}%') 
                AND status > 1)");

        $query4 = DB::table('suratkeluar_riwayat as z')
            ->selectRaw("DISTINCT a.jenis_id,
                         a.suratkeluar_id as surat_id,
                         MONTH(z.tgl_update) as bulan,
                         YEAR(z.tgl_update) as tahun")
            ->leftJoin('suratkeluar_isi as a', function ($join) {
                $join->on('z.suratkeluar_id', '=', 'a.suratkeluar_id')
                    ->on('z.revisi_id', '=', 'a.revisi_id');
            })
            ->leftJoin('suratkeluar_cc as e', function ($join) {
                $join->on('z.suratkeluar_id', '=', 'e.suratkeluar_id')
                    ->on('z.revisi_id', '=', 'e.revisi_id')
                    ->on('z.nourut_riw', '=', 'e.nourut_riw');
            })
            ->where('e.satkerid_tujuan', 'like', $satkerid . '%')
            ->where('e.last_sent', 1)
            ->where('z.status', '>', 1);

        // Gabungkan ke satu union
        $unionQuery = $query1->unionAll($query2)->unionAll($query3)->unionAll($query4);

        // Bungkus union sebagai subquery
        $wrappedQuery = DB::table(DB::raw("({$unionQuery->toSql()}) as t"))
            ->mergeBindings($unionQuery)
            ->where('t.jenis_id', 'like', $jenisid)
            ->where('t.tahun', 'like', $tahun)
            ->select('t.bulan', DB::raw('COUNT(t.surat_id) as jumlah'))
            ->groupBy('t.bulan')
            ->get()
            ->keyBy('bulan');

        // Buat hasil akhir dengan nama bulan
        $result = collect($bulanArray)->map(function ($bulan) use ($wrappedQuery) {
            $nama_bulan = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ][$bulan];

            return [
                'bulan' => $bulan,
                'nama_bulan' => $nama_bulan,
                'jumlah' => $wrappedQuery->get($bulan)->jumlah ?? 0,
            ];
        });

        return $result;
    }

    public function aktivitas(Request $request, AktivitasDataTable $dataTable)
    {
        $loginUser = auth()->user();
        $isOperator = $loginUser->usergroupid == 1;
        $kodesatker = null;
        if (!$isOperator) {
            $loginSatker = \App\Models\MasterSatker::where('userid', $loginUser->id)->first();
            $kodesatker = $loginSatker ? $loginSatker->kodesatker : null;
        }
        if ($isOperator) {
            $users = \App\Models\User::all();
        } else if ($kodesatker) {
            $users = \App\Models\User::whereHas('masterSatker', function($q) use ($kodesatker) {
                $q->where('kodesatker', 'like', $kodesatker . '%')
                  ->whereRaw('LENGTH(kodesatker) > ?', [strlen($kodesatker)]);
            })->get();
        } else {
            $users = collect();
        }
        return $dataTable->render('report.aktivitas', compact('users'));
    }

    public function cetak(Request $request)
    {
        $suratType = $request->input('surat_type', 'surat_masuk');

        if (in_array($suratType, ['surat_masuk', 'entry_surat'])) {
            $query = DB::table('entry_surat_isis')->select([
                'id',
                'noagenda',
                'sifat',
                'jenis_id',
                'nomor_surat',
                'dari',
                'kepada',
                'hal',
                'created_by',
                'tgl_surat',
            ]);
        } elseif (in_array($suratType, ['surat_keluar', 'surat_terkirim'])) {
            $query = DB::table('surat_keluar_isis')->select([
                'id',
                DB::raw("'' as noagenda"),
                'sifat',
                'jenis_id',
                'nosurat as nomor_surat',
                'ttd_nama as dari',
                'kepada',
                'hal',
                'user_id_pembuat as created_by',
                'tgl_surat',
            ]);
        } else {
            $query = DB::table('entry_surat_isis')->select([
                'id',
                'noagenda',
                'sifat',
                'jenis_id',
                'nomor_surat',
                'dari',
                'kepada',
                'hal',
                'created_by',
                'tgl_surat',
            ]);
        }

        // Optional filter by tanggal atau jenis
        if ($request->filled('tgl_surat')) {
            $query->whereDate('tgl_surat', $request->tgl_surat);
        }

        if ($request->filled('jenis_surat')) {
            $query->where('jenis_id', $request->jenis_surat);
        }

        $data = $query->orderBy('tgl_surat', 'desc')->get();

        return view('report.cetak', compact('data'));
    }
}
