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
        // Import Master Jenis Surat
        echo "Importing Master Jenis Surat...\n";
        DB::connection('mysql2')->table('master_jenissurat')->orderBy('jenis_id')->chunk(1000, function ($records) {
            foreach ($records as $q) {
                MasterJenisSurat::create([
                    'last_id' => $q->jenis_id,
                    'name' => $q->jenis,
                ]);
            }
        });

        // Import Master Klasifikasi
        echo "Importing Master Klasifikasi...\n";
        DB::connection('mysql2')->table('master_klasifikasi')->orderBy('kodeklasifikasi')->chunk(1000, function ($records) {
            foreach ($records as $q) {
                MasterKlasifikasi::create([
                    'kodeklasifikasi' => $q->kodeklasifikasi,
                    'klasifikasi' => $q->klasifikasi,
                    'retensi_aktif' => $q->retensi_aktif,
                    'retensi_inaktif' => $q->retensi_inaktif,
                    'keterangan' => $q->keterangan,
                    'retensi' => $q->retensi,
                    'parent' => $q->parent,
                ]);
            }
        });

        // Import Master Instansi
        echo "Importing Master Instansi...\n";
        DB::connection('mysql2')->table('master_instansi')->orderBy('instansiid')->chunk(1000, function ($records) {
            foreach ($records as $q) {
                MasterInstansi::create([
                    'last_id' => $q->instansiid,
                    'instansi' => $q->instansi,
                    'kepala' => $q->kepala,
                    'alamat' => $q->alamat,
                    'kota' => $q->kota,
                    'telp' => $q->telp,
                ]);
            }
        });

        // Import Master Satker
        echo "Importing Master Satker...\n";
        DB::connection('mysql2')->table('master_satker')->orderBy('satkerid')->chunk(1000, function ($records) {
            foreach ($records as $q) {
                MasterSatker::create([
                    'satkerid' => $q->satkerid,
                    'kodesatker' => $q->kodesatker,
                    'satker' => $q->satker,
                    'eselon' => $q->eselon,
                    'userid' => User::find($q->userid)->id ?? null,
                ]);
            }
        });

        echo "Master data imported successfully!\n";
    }
}
