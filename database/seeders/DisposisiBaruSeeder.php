<?php

namespace Database\Seeders;

use App\Models\DisposisiBaru;
use App\Models\MasterTindakanDisposisi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisposisiBaruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil mapping ID dari cache
        $entrySuratIdMapping = cache('entrysurat_id_mapping', []);
        $tindakanMapping = cache('master_tindakan_disposisi_id_mapping', []);
        
        // Process dalam batch untuk menghemat memory
        $batchSize = 1000;
        $totalRecords = DB::connection('mysql2')->table('disposisi_isi')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";
        
        DB::connection('mysql2')->table('disposisi_isi')->orderBy('disposisi_id')->chunk($batchSize, function ($records) use ($entrySuratIdMapping, $tindakanMapping, &$processedRecords, $totalRecords) {
            foreach ($records as $q) {
                // Skip jika entrysurat_id tidak ada dalam mapping
                if (!isset($entrySuratIdMapping[$q->entrysurat_id])) {
                    continue;
                }
                
                $now = now();
                $tgl_remitten = ($q->tgl_remiten == '0000-00-00 00:00:00' || empty($q->tgl_remiten)) ? null : $q->tgl_remiten;
                
                // Map user ID dari database lama ke database baru
                $dari_id = null;
                if ($q->userid_pembuat) {
                    $user = User::find($q->userid_pembuat);
                    $dari_id = $user ? $user->id : null;
                }
                
                // Skip jika dari_id null karena foreign key constraint
                if (!$dari_id) {
                    continue;
                }
                
                $newEntry = DisposisiBaru::create([
                    'entrysurat_id' => $entrySuratIdMapping[$q->entrysurat_id],
                    'dari_id' => $dari_id,
                    'kepada' => $q->kepada,
                    'remitten' => $tgl_remitten,
                    'content' => $q->isi,
                ]);
                
                // Jika ada tindakan, tambahkan ke pivot table
                if (!empty($q->tindakan)) {
                    // Cari tindakan yang sesuai atau buat baru
                    $tindakan = MasterTindakanDisposisi::firstOrCreate([
                        'tindakan' => $q->tindakan,
                        'satkerid' => $q->satkerid_pembuat,
                    ]);
                    
                    // Attach tindakan ke disposisi
                    $newEntry->tindakans()->attach($tindakan->id);
                }
            }
            
            $processedRecords += count($records);
            echo "Processed {$processedRecords}/{$totalRecords} records...\n";
        });
        
        echo "Completed processing all records.\n";
    }
} 