<?php

namespace Database\Seeders;

use App\Models\MasterTindakanDisposisi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterTindakanDisposisiSeeder extends Seeder
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
        $totalRecords = DB::connection('mysql2')->table('master_tindakandisposisi')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";

        DB::connection('mysql2')->table('master_tindakandisposisi')->orderBy('TindakanID')->chunk($batchSize, function ($records) use (&$oldToNewIdMapping, &$processedRecords, $totalRecords) {
            foreach ($records as $q) {
                $newEntry = MasterTindakanDisposisi::create([
                    'last_id' => $q->TindakanID,
                    'tindakan' => $q->Tindakan,
                    'satkerid' => $q->SatkerID,
                ]);

                // Simpan mapping ID lama ke ID baru
                $oldToNewIdMapping[$q->TindakanID] = $newEntry->id;
            }
            
            $processedRecords += count($records);
            echo "Processed {$processedRecords}/{$totalRecords} records...\n";
        });

        // Simpan mapping ke cache untuk digunakan seeder lain
        cache(['master_tindakan_disposisi_id_mapping' => $oldToNewIdMapping], now()->addHours(1));
        
        echo "Completed processing all records.\n";
    }
}
