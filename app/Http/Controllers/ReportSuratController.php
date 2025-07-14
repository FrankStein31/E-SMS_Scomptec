<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisSurat;
use App\Models\MasterSatker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
    public function surat(Request $request)
    {
        $jenisSurat = MasterJenisSurat::all();
        $satker = MasterSatker::all();
        return view('report.surat', compact(
            'jenisSurat',
            'satker'
        ));
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
        $data = self::getStatistik(Auth::user()->id, '00', '0');
        $jenisSurat = MasterJenisSurat::all();

        return view('report.statistik', compact(
            "data", "jenisSurat"
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

        $sql = <<<SQL
    SELECT 
        b.bulan, 
        CASE b.bulan 
            WHEN 1 THEN 'Januari' WHEN 2 THEN 'Februari' WHEN 3 THEN 'Maret'
            WHEN 4 THEN 'April' WHEN 5 THEN 'Mei' WHEN 6 THEN 'Juni'
            WHEN 7 THEN 'Juli' WHEN 8 THEN 'Agustus' WHEN 9 THEN 'September'
            WHEN 10 THEN 'Oktober' WHEN 11 THEN 'November' WHEN 12 THEN 'Desember'
        END AS nama_bulan,
        IFNULL(COUNT(t.surat_id), 0) AS jumlah
    FROM (
        SELECT 1 AS bulan UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION 
        SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION 
        SELECT 11 UNION SELECT 12
    ) b
    LEFT JOIN (
        SELECT DISTINCT 
            CASE WHEN b.jenis_id = 0 THEN -1 ELSE b.jenis_id END AS jenis_id,
            b.id AS surat_id,
            MONTH(b.tgl_diarahkan) AS bulan,
            YEAR(b.tgl_diarahkan) AS tahun
        FROM entry_surat_isis b
        LEFT JOIN entry_surat_tujuans a ON b.id = a.entrysurat_id
        WHERE a.satkerid_tujuan LIKE CONCAT(:satkerid1, '%') OR b.satkerid_pembuat LIKE CONCAT(:satkerid2, '%')

        UNION ALL

        SELECT DISTINCT 
            0 AS jenis_id, 
            b.disposisi_id AS surat_id,
            MONTH(b.tgl_disposisi) AS bulan,
            YEAR(b.tgl_disposisi) AS tahun
        FROM disposisi_isi b
        LEFT JOIN disposisi_tujuan a ON b.disposisi_id = a.disposisi_id
        WHERE a.satkerid_tujuan LIKE CONCAT(:satkerid3, '%') OR b.satkerid_pembuat LIKE CONCAT(:satkerid4, '%')

        UNION ALL

        SELECT DISTINCT 
            a.jenis_id,
            a.suratkeluar_id AS surat_id,
            MONTH(z.tgl_update) AS bulan,
            YEAR(z.tgl_update) AS tahun
        FROM suratkeluar_riwayat z
        LEFT JOIN suratkeluar_isi a 
            ON z.suratkeluar_id = a.suratkeluar_id AND z.revisi_id = a.revisi_id
        WHERE (
            z.satkerid_pembuat LIKE CONCAT(:satkerid5, '%') OR 
            (z.satkerid_tujuan LIKE CONCAT(:satkerid6, '%') AND z.last_sent = 1) OR 
            z.satkerid_final LIKE CONCAT(:satkerid7, '%')
        )
        AND z.status > 1
        AND z.nourut_riw = (
            SELECT MAX(nourut_riw)
            FROM suratkeluar_riwayat
            WHERE suratkeluar_id = z.suratkeluar_id
              AND (
                satkerid_pembuat LIKE CONCAT(:satkerid8, '%') OR 
                (satkerid_tujuan LIKE CONCAT(:satkerid9, '%') AND last_sent = 1) OR 
                satkerid_final LIKE CONCAT(:satkerid10, '%')
              )
              AND status > 1
        )

        UNION ALL

        SELECT DISTINCT 
            a.jenis_id,
            a.suratkeluar_id AS surat_id,
            MONTH(z.tgl_update) AS bulan,
            YEAR(z.tgl_update) AS tahun
        FROM suratkeluar_riwayat z
        LEFT JOIN suratkeluar_isi a 
            ON z.suratkeluar_id = a.suratkeluar_id AND z.revisi_id = a.revisi_id
        LEFT JOIN suratkeluar_cc e 
            ON z.suratkeluar_id = e.suratkeluar_id AND z.revisi_id = e.revisi_id AND z.nourut_riw = e.nourut_riw
        WHERE e.satkerid_tujuan LIKE CONCAT(:satkerid11, '%') AND e.last_sent = 1 AND z.status > 1
    ) t ON b.bulan = t.bulan
    WHERE t.jenis_id LIKE :jenisid AND t.tahun LIKE :tahun
    GROUP BY b.bulan
SQL;


        $params = [
            'satkerid1' => $v_satkerid,
            'satkerid2' => $v_satkerid,
            'satkerid3' => $v_satkerid,
            'satkerid4' => $v_satkerid,
            'satkerid5' => $v_satkerid,
            'satkerid6' => $v_satkerid,
            'satkerid7' => $v_satkerid,
            'satkerid8' => $v_satkerid,
            'satkerid9' => $v_satkerid,
            'satkerid10' => $v_satkerid,
            'satkerid11' => $v_satkerid,
            'jenisid'   => $v_jenisid,
            'tahun'     => $v_tahun
        ];
        $result = DB::select($sql, $params);

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
}
