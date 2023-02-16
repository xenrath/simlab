<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shifts = [
            [
                'nama' => 'Shift 1',
                'waktu' => '08.00 - 11.00'
            ],
            [
                'nama' => 'Shift 2',
                'waktu' => '11.00 - 14.00'
            ],
            [
                'nama' => 'Shift 3',
                'waktu' => '14.00 - 16.00'
            ],
        ];

        Shift::insert($shifts);
    }
}
