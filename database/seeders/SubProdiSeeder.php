<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\SubProdi;
use Illuminate\Database\Seeder;

class SubProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prodis = [
            [
                'jenjang' => 'D3',
                'nama' => 'Kebidanan',
                'prodi_id' => '1',
            ],
            [
                'jenjang' => 'D3',
                'nama' => 'Keperawatan',
                'prodi_id' => '2'
            ],
            [
                'jenjang' => 'D4',
                'nama' => 'Keselamatan dan Kesehatan Kerja',
                'prodi_id' => '3'
            ],
            [
                'jenjang' => 'S1',
                'nama' => 'Ilmu Keperawatan',
                'prodi_id' => '2'
            ],
            [
                'jenjang' => 'S1',
                'nama' => 'Farmasi',
                'prodi_id' => '4'
            ],
            [
                'jenjang' => 'Profesi',
                'nama' => 'Ners',
                'prodi_id' => '2'
            ],
        ];

        SubProdi::insert($prodis);
    }
}
