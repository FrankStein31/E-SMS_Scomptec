<?php

namespace Database\Seeders;

use App\Models\MasterSatker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasterSatkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $master_satker = DB::connection('mysql2')->table('master_satker')->get()->map(function ($q){
            $data = MasterSatker::create([
                'satkerid' => $q->satkerid,
                'kodesatker' => $q->kodesatker,
                'satker' => $q->satker,
                'eselon' => $q->eselon,
                'userid' => $q->userid,
            ]);
        });
        
    }
}
