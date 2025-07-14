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
        DB::connection('mysql2')->table('master_tindakandisposisi')->get()->map(function ($q) {
            MasterTindakanDisposisi::create([
                'tindakan' => $q->Tindakan,
                'satkerid' => $q->SatkerID,
            ]);
        });
    }
}
