<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
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
                'kode' => 'BD',
                'nama' => 'kebidanan',
                'singkatan' => 'bidan'
            ],
            [
                'kode' => 'PW',
                'nama' => 'keperawatan',
                'singkatan' => 'perawat'
            ],
            [
                'kode' => 'K3',
                'nama' => 'keselamatan dan kesehatan kerja',
                'singkatan' => 'k3'
            ],
            [
                'kode' => 'FM',
                'nama' => 'farmasi',
                'singkatan' => 'farmasi'
            ],
            [
                'kode' => 'LC',
                'nama' => 'lab. central',
                'singkatan' => 'central'
            ],
            [
                'kode' => 'GB',
                'nama' => 'gudang bahan',
                'singkatan' => 'bahan'
            ],
        ];

        Prodi::insert($prodis);
    }
}
