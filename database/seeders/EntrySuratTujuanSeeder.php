<?php

namespace Database\Seeders;

use App\Models\EntrySuratTujuan;
use App\Models\EntrySuratIsi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySuratTujuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil mapping ID dari cache
        $idMapping = cache('entrysurat_id_mapping', []);
        
        // Process dalam batch untuk menghemat memory
        $batchSize = 1000;
        $totalRecords = DB::connection('mysql2')->table('entrysurat_tujuan')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";
        
        DB::connection('mysql2')->table('entrysurat_tujuan')->orderBy('entrysurat_id')->chunk($batchSize, function ($records) use ($idMapping, &$processedRecords, $totalRecords) {
            $insertData = [];
            
            foreach ($records as $q) {
                // Gunakan mapping ID yang sudah disimpan
                if (isset($idMapping[$q->entrysurat_id])) {
                    // Map user ID dari database lama ke database baru
                    $userid_tujuan = User::find($q->userid_tujuan)->id ?? $q->userid_tujuan;
                    
                    $insertData[] = [
                        'id' => \Illuminate\Support\Str::ulid(),
                        'entrysurat_id' => $idMapping[$q->entrysurat_id],
                        'satkerid_tujuan' => $q->satkerid_tujuan,
                        'dibaca' => (bool) $q->dibaca,
                        'is_tembusan' => (bool) $q->is_tembusan,
                        'userid_tujuan' => $userid_tujuan,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // Bulk insert untuk performance yang lebih baik
            if (!empty($insertData)) {
                DB::table('entry_surat_tujuans')->insert($insertData);
            }
            
            $processedRecords += count($records);
            echo "Processed {$processedRecords}/{$totalRecords} records...\n";
            
            // Clear memory
            unset($insertData);
            gc_collect_cycles();
        });
        
        echo "Completed processing all records.\n";
    }
}
