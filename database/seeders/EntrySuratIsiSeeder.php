<?php

namespace Database\Seeders;

use App\Models\EntrySuratIsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySuratIsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = DB::connection('mysql2')->table('entrysurat_isi')->limit(50)->get()->map(function ($q){
            $now = now();
            $tgl_surat = ($q->tgl_surat == '0000-00-00 00:00:00' || empty($q->tgl_surat)) ? $now : $q->tgl_surat;
            $tgl_diterima = ($q->tgl_terima == '0000-00-00 00:00:00' || empty($q->tgl_terima)) ? $now : $q->tgl_terima;
            $tgl_diarahkan = ($q->tgl_diarahkan == '0000-00-00 00:00:00' || empty($q->tgl_diarahkan)) ? $now : $q->tgl_diarahkan;
            $tgl_update = ($q->tgl_update == '0000-00-00 00:00:00' || empty($q->tgl_update)) ? $now : $q->tgl_update;
            $insert = EntrySuratIsi::create([
                'jenis_id' => $q->jenis_id,
                'nomor_surat' => $q->nosurat,
                'kode_klasifikasi' => $q->kodeklasifikasi,
                'hal' => $q->hal,
                'kepada' => $q->kepada,
                'dari' => $q->dari,
                'alamat' => $q->alamat,
                'tgl_surat' => $tgl_surat,
                'tgl_diterima' => $tgl_diterima,
                'tgl_diarahkan' => $tgl_diarahkan,
                'sifat' => $q->sifat,
                'isi' => $q->isi,
                'tembusan' => $q->tembusan,
                'isfinal' => $q->isfinal,
                'created_by' => $q->userid_pembuat,
                'satkerid_pembuat' => $q->satkerid_pembuat,
                'jumlah_lampiran' => $q->jml_lampiran,
                'referensi_id' => $q->referensi_id,
                'noagenda' => $q->noagenda,
                'tgl_update' => $tgl_update,
                'updated_by' => null,
                'satkerid_update' => $q->satkerid_update,
                'terdisposisi' => $q->terdisposisi,
            ]);
        });
    }
}
