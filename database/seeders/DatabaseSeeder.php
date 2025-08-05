<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->command->info('Starting database seeding...');
        
        $this->call([
            UserSeeder::class,
            MasterSeeder::class,
            MasterTindakanDisposisiSeeder::class,
            EntrySuratIsiSeeder::class,
            EntrySuratLampiranSeeder::class,
            EntrySuratTujuanSeeder::class,
            EntrySuratScanSeeder::class,
            DisposisiSeeder::class,
            DisposisiBaruSeeder::class,
        ]);
        
        $this->command->info('Database seeding completed!');
    }
}
