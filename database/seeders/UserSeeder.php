<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Process dalam batch untuk menghemat memory
        $batchSize = 1000;
        $totalRecords = DB::connection('mysql2')->table('master_user')->count();
        $processedRecords = 0;
        
        echo "Processing {$totalRecords} records in batches of {$batchSize}...\n";
        
        DB::connection('mysql2')->table('master_user')->orderBy('userid')->chunk($batchSize, function ($records) use (&$processedRecords, $totalRecords) {
            foreach ($records as $q) {
            User::create([
                'id' => $q->userid,
                'username' => $q->username,
                'fullname' => $q->fullname,
                'jabatan' => $q->jabatan,
                'satkerid' => $q->satkerid,
                'nip' => $q->nip,
                'usergroupid' => $q->usergroupid,
                'email' => $q->email,
                'email_verified_at' => null,
                'last_notif' => $q->last_notif,
                'pangkat' => $q->pangkat,
                'password' => Hash::make("password"),
            ]);
            }
            
            $processedRecords += count($records);
            echo "Processed {$processedRecords}/{$totalRecords} records...\n";
        });
        
        echo "Completed processing all records.\n";
    }
}
