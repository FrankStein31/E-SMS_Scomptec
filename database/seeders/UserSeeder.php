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
        $data = DB::connection('mysql2')->table('master_user')->get()->map(function ($q) {
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
        });
        
    }
}
