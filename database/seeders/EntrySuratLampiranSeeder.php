<?php

namespace Database\Seeders;

use App\Models\EntrySuratLampiran;
use App\Models\EntrySuratIsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySuratLampiranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil mapping ID dari cache
        $idMapping = cache('entrysurat_id_mapping', []);
        
        $data = DB::connection('mysql2')->table('entrysurat_lampiran')->get()->map(function ($q) use ($idMapping) {
            // Gunakan mapping ID yang sudah disimpan
            if (isset($idMapping[$q->entrysurat_id])) {
                $tgl_upload = ($q->tgl_upload == '0000-00-00 00:00:00' || empty($q->tgl_upload)) ? now() : $q->tgl_upload;
                
                EntrySuratLampiran::create([
                    'entrysurat_id' => $idMapping[$q->entrysurat_id],
                    'nama_lampiran' => $q->nama_lampiran,
                    'nama_file' => $q->nama_file,
                    'size' => $q->size,
                    'tgl_upload' => $tgl_upload,
                ]);
            }
        });
    }
}
