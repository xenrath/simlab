<?php

namespace Database\Seeders;

use App\Models\Praktik;
use Illuminate\Database\Seeder;

class PraktikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $praktiks = [
            [
                'nama' => 'Praktik Laboratorium',
            ],
            [
                'nama' => 'Praktik Dalam Kelas'
            ],
            [
                'nama' => 'Praktik Klinik / Rumah Sakit'
            ]
        ];

        Praktik::insert($praktiks);
    }
}
