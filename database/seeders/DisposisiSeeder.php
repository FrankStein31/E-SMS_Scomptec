<?php

namespace Database\Seeders;

use App\Models\Disposisi;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisposisiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil mapping ID dari cache
        $entrySuratIdMapping = cache('entrysurat_id_mapping', []);
        
        // Membuat mapping ID untuk parent_id
        $oldToNewDisposisiIdMapping = [];
        
        // Process dalam batch untuk menghemat memory
        $batchSize = 1000;
        $totalRecords = DB::connection('mysql2')->table('disposisi_isi')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";
        
        DB::connection('mysql2')->table('disposisi_isi')->orderBy('disposisi_id')->chunk($batchSize, function ($records) use ($entrySuratIdMapping, &$oldToNewDisposisiIdMapping, &$processedRecords, $totalRecords) {
            foreach ($records as $q) {
                // Skip jika entrysurat_id tidak ada dalam mapping
                if (!isset($entrySuratIdMapping[$q->entrysurat_id])) {
                    continue;
                }
                
                $now = now();
                $tgl_disposisi = ($q->tgl_disposisi == '0000-00-00 00:00:00' || empty($q->tgl_disposisi)) ? $now : $q->tgl_disposisi;
                $tgl_remitten = ($q->tgl_remiten == '0000-00-00 00:00:00' || empty($q->tgl_remiten)) ? null : $q->tgl_remiten;
                
                // Map user ID dari database lama ke database baru
                $userid_pembuat = null;
                if ($q->userid_pembuat) {
                    $user = User::find($q->userid_pembuat);
                    $userid_pembuat = $user ? $user->id : null;
                }
                
                // Skip jika userid_pembuat null karena foreign key constraint
                if (!$userid_pembuat) {
                    continue;
                }
                
                // Handle parent_id - akan di-update setelah semua data diinsert
                $parent_id = $q->parent_id != 0 ? $q->parent_id : null;
                
                $newEntry = Disposisi::create([
                    'last_id' => $q->disposisi_id,
                    'entrysurat_id' => $entrySuratIdMapping[$q->entrysurat_id],
                    'parent_id' => null, // Akan diupdate nanti
                    'kodeklasifikasi' => $q->kodeklasifikasi,
                    'kepada' => substr($q->kepada, 0, 255), // Batasi maksimal 255 karakter
                    'hal' => substr($q->hal, 0, 255), // Batasi maksimal 255 karakter
                    'tgl_disposisi' => $tgl_disposisi,
                    'tgl_remitten' => $tgl_remitten,
                    'status' => $q->sifat,
                    'isi' => $q->isi,
                    'tindakan' => $q->tindakan,
                    'userid_pembuat' => $userid_pembuat,
                    'satkerid_pembuat' => $q->satkerid_pembuat,
                    'terdisposisi' => $q->terdisposisi,
                    'mig_nourut' => $q->mig_nourut,
                    'mig_satkerasalid' => $q->mig_satkerasalid,
                    'mig_satkertujuanid' => $q->mig_satkertujuanid,
                    'mig_terbaca' => $q->mig_terbaca,
                    'mig_nourutasal' => $q->mig_nourutasal,
                ]);
                
                // Simpan mapping ID lama ke ID baru
                $oldToNewDisposisiIdMapping[$q->disposisi_id] = [
                    'new_id' => $newEntry->id,
                    'old_parent_id' => $parent_id
                ];
            }
            
            $processedRecords += count($records);
            echo "Processed {$processedRecords}/{$totalRecords} records...\n";
        });
        
        // Update parent_id setelah semua data diinsert
        foreach ($oldToNewDisposisiIdMapping as $oldId => $data) {
            if ($data['old_parent_id'] && isset($oldToNewDisposisiIdMapping[$data['old_parent_id']])) {
                Disposisi::where('id', $data['new_id'])
                    ->update(['parent_id' => $oldToNewDisposisiIdMapping[$data['old_parent_id']]['new_id']]);
            }
        }
        
        // Simpan mapping ke cache untuk digunakan seeder lain
        cache(['disposisi_id_mapping' => $oldToNewDisposisiIdMapping], now()->addHours(1));
        
        echo "Completed processing all records.\n";
    }
}
 