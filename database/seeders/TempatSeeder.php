<?php

namespace Database\Seeders;

use App\Models\Tempat;
use Illuminate\Database\Seeder;

class TempatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tempats = [
            [
                'kode' => 'G1',
                'nama' => 'Lab. Terpadu',
            ],
            [
                'kode' => 'G2',
                'nama' => 'Gedung Farmasi',
            ],
        ];

        Tempat::insert($tempats);
    }
}
