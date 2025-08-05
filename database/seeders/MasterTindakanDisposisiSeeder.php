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

        $data = DB::connection('mysql2')->table('master_tindakandisposisi')->get()->each(function ($q) use (&$oldToNewIdMapping) {
            $newEntry = MasterTindakanDisposisi::create([
                'tindakan' => $q->Tindakan,
                'satkerid' => $q->SatkerID,
            ]);

            // Simpan mapping ID lama ke ID baru
            $oldToNewIdMapping[$q->TindakanID] = $newEntry->id;
        });

        // Simpan mapping ke cache untuk digunakan seeder lain
        cache(['master_tindakan_disposisi_id_mapping' => $oldToNewIdMapping], now()->addHours(1));
    }
}
