<?php

namespace Database\Seeders;

use App\Models\EntrySuratIsi;
use App\Models\User;
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
        // Membuat mapping ID untuk referensi di seeder lain
        $oldToNewIdMapping = [];

        // Process dalam batch untuk menghemat memory
        $batchSize = 1000;
        $totalRecords = DB::connection('mysql2')->table('entrysurat_isi')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";

        DB::connection('mysql2')->table('entrysurat_isi')->orderBy('entrysurat_id')->chunk($batchSize, function ($records) use (&$oldToNewIdMapping, &$processedRecords, $totalRecords) {
            foreach ($records as $q) {
                $now = now();
                $tgl_surat = ($q->tgl_surat == '0000-00-00 00:00:00' || empty($q->tgl_surat)) ? $now : $q->tgl_surat;
                $tgl_diterima = ($q->tgl_terima == '0000-00-00 00:00:00' || empty($q->tgl_terima)) ? $now : $q->tgl_terima;
                $tgl_diarahkan = ($q->tgl_diarahkan == '0000-00-00 00:00:00' || empty($q->tgl_diarahkan)) ? $now : $q->tgl_diarahkan;
                $tgl_update = ($q->tgl_update == '0000-00-00 00:00:00' || empty($q->tgl_update)) ? $now : $q->tgl_update;

                // Map user ID dari database lama ke database baru
                $created_by = null;
                if ($q->userid_pembuat) {
                    $user = User::find($q->userid_pembuat);
                    $created_by = $user ? $user->id : null;
                }

                $updated_by = null;
                if ($q->userid_update && $q->userid_update != 0) {
                    $user = User::find($q->userid_update);
                    $updated_by = $user ? $user->id : null;
                }

                // Skip jika created_by null karena foreign key constraint
                if (!$created_by) {
                    continue;
                }

                $newEntry = EntrySuratIsi::create([
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
                    'created_by' => $created_by,
                    'satkerid_pembuat' => $q->satkerid_pembuat,
                    'jumlah_lampiran' => $q->jml_lampiran,
                    'referensi_id' => $q->referensi_id,
                    'noagenda' => $q->noagenda,
                    'tgl_update' => $tgl_update,
                    'updated_by' => $updated_by,
                    'satkerid_update' => $q->satkerid_update,
                    'terdisposisi' => $q->terdisposisi,
                ]);

                // Simpan mapping ID lama ke ID baru
                $oldToNewIdMapping[$q->entrysurat_id] = $newEntry->id;
            }
            
            $processedRecords += count($records);
            echo "Processed {$processedRecords}/{$totalRecords} records...\n";
        });

        // Simpan mapping ke cache untuk digunakan seeder lain
        cache(['entrysurat_id_mapping' => $oldToNewIdMapping], now()->addHours(1));
        
        echo "Completed processing all records.\n";
    }
}
