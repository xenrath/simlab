<?php

namespace Database\Seeders;

use App\Models\Satuan;
use Illuminate\Database\Seeder;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $satuans = [
            [
                'nama' => 'liter',
                'singkatan' => 'L',
                'kali' => '1',
                'kategori' => 'volume'
            ],
            [
                'nama' => 'mililiter',
                'singkatan' => 'mL',
                'kali' => '1000',
                'kategori' => 'volume'
            ],
            [
                'nama' => 'kilogram',
                'singkatan' => 'Kg',
                'kali' => '1',
                'kategori' => 'berat'
            ],
            [
                'nama' => 'gram',
                'singkatan' => 'g',
                'kali' => '1000',
                'kategori' => 'berat'
            ],
            [
                'nama' => 'miligram',
                'singkatan' => 'mg',
                'kali' => '1000000',
                'kategori' => 'berat'
            ],
            [
                'nama' => 'pcs',
                'singkatan' => 'Pcs',
                'kali' => '1',
                'kategori' => 'barang'
            ],
            [
                'nama' => 'roll',
                'singkatan' => 'Roll',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'box',
                'singkatan' => 'Box',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'pack',
                'singkatan' => 'Pack',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'cubicle centimeter',
                'singkatan' => 'Cc',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'pasang',
                'singkatan' => 'Psg',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'biji',
                'singkatan' => 'Biji',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'Can',
                'singkatan' => 'Can',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'ekor',
                'singkatan' => 'Ekor',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'botol',
                'singkatan' => 'Botol',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'tabung',
                'singkatan' => 'Tabung',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'lembar',
                'singkatan' => 'Lembar',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'kit',
                'singkatan' => 'Kit',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'fls',
                'singkatan' => 'Fls',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'gulung',
                'singkatan' => 'Gulung',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
            [
                'nama' => 'set',
                'singkatan' => 'Set',
                'kali' => '1',
                'kategori' => 'bahan',
            ],
        ];

        Satuan::insert($satuans);
    }
}
