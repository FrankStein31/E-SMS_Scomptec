<?php

namespace Database\Seeders;

use App\Models\MasterInstansi;
use App\Models\MasterJenisSurat;
use App\Models\MasterKlasifikasi;
use App\Models\MasterSatker;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MasterJenisSurat::create([
            'last_id' => '0',
            'name' => 'Surat Masuk',
        ]);
        DB::connection('mysql2')->table('master_jenissurat')->get()->map(function ($q) {
            MasterJenisSurat::create([
                'last_id' => $q->jenis_id,
                'name' => $q->jenis,
            ]);
        });

        DB::connection('mysql2')->table('master_klasifikasi')->get()->map(function ($q) {
            MasterKlasifikasi::create([
                'kodeklasifikasi' => $q->kodeklasifikasi,
                'klasifikasi' => $q->klasifikasi,
                'retensi_aktif' => $q->retensi_aktif,
                'retensi_inaktif' => $q->retensi_inaktif,
                'keterangan' => $q->keterangan,
                'retensi' => $q->retensi,
                'parent' => $q->parent,
            ]);
        });

        DB::connection('mysql2')->table('master_instansi')->get()->map(function ($q) {
            MasterInstansi::create([
                'last_id' => $q->instansiid,
                'instansi' => $q->instansi,
                'kepala' => $q->kepala,
                'alamat' => $q->alamat,
                'kota' => $q->kota,
                'telp' => $q->telp,
            ]);
        });

        DB::connection('mysql2')->table('master_satker')->get()->map(function ($q) {
            MasterSatker::create([
                'satkerid' => $q->satkerid,
                'kodesatker' => $q->kodesatker,
                'satker' => $q->satker,
                'eselon' => $q->eselon,
                'userid' => User::find($q->userid)->id ?? null,
            ]);
        });


    }
}
