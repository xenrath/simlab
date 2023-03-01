<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            TempatSeeder::class,
            ProdiSeeder::class,
            SubProdiSeeder::class,
            UserSeeder::class,
            RuangSeeder::class,
            SatuanSeeder::class,
            // BarangSeeder::class,
            // BahanSeeder::class,
            ShiftSeeder::class,
            PraktikSeeder::class
        ]);
    }
}
