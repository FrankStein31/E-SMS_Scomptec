<?php

namespace Database\Seeders;

use App\Models\EntrySuratScan;
use App\Models\EntrySuratIsi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntrySuratScanSeeder extends Seeder
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
        $totalRecords = DB::connection('mysql2')->table('entrysurat_scan')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";
        
        DB::connection('mysql2')->table('entrysurat_scan')->orderBy('scan_id')->chunk($batchSize, function ($records) use ($idMapping, &$processedRecords, $totalRecords) {
            $insertData = [];
            
            foreach ($records as $q) {
                // Gunakan mapping ID yang sudah disimpan
                if (isset($idMapping[$q->entrysurat_id])) {
                    $tgl_upload = ($q->tgl_upload == '0000-00-00 00:00:00' || empty($q->tgl_upload)) ? now() : $q->tgl_upload;
                    
                    $insertData[] = [
                        'id' => \Illuminate\Support\Str::ulid(),
                        'entrysurat_id' => $idMapping[$q->entrysurat_id],
                        'nourut' => $q->nourut,
                        'nama_scan' => $q->nama_scan,
                        'nama_file' => $q->nama_file,
                        'size' => $q->size,
                        'tgl_upload' => $tgl_upload,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            // Bulk insert untuk performance yang lebih baik
            if (!empty($insertData)) {
                DB::table('entry_surat_scans')->insert($insertData);
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
