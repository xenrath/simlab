<?php

namespace Database\Seeders;

use App\Models\Ruang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class RuangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $ruangs = [
            [
                'kode' => '01',
                'nama' => 'Lab. Central',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '5',
                'laboran_id' => '15'
            ],
            [
                'kode' => '02',
                'nama' => 'Gudang Bahan',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '6',
                'laboran_id' => '14'
            ],
            [
                'kode' => '03',
                'nama' => 'Ruang Anak',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '4'
            ],
            [
                'kode' => '04',
                'nama' => 'Ruang Medikal',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '4'
            ],
            [
                'kode' => '05',
                'nama' => 'Ruang Bedah',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '4'
            ],
            [
                'kode' => '06',
                'nama' => 'Ruang Biomedik',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '5'
            ],
            [
                'kode' => '07',
                'nama' => 'Ruang Kritis',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '5'
            ],
            [
                'kode' => '08',
                'nama' => 'Ruang Komunitas',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '5'
            ],
            [
                'kode' => '09',
                'nama' => 'Ruang Gadar',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '6'
            ],
            [
                'kode' => '10',
                'nama' => 'Ruang Gerontik',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '6'
            ],
            [
                'kode' => '11',
                'nama' => 'Ruang OSCA',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '6'
            ],
            [
                'kode' => '12',
                'nama' => 'Ruang Keperawatan Dasar',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '7'
            ],
            [
                'kode' => '13',
                'nama' => 'Ruang Keperawatan Jiwa',
                'tempat_id' => '1',
                'lantai' => 'L2',
                'prodi_id' => '2',
                'laboran_id' => '7'
            ],
            [
                'kode' => '14',
                'nama' => 'Lab. Kesehatan Kerja',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '3',
                'laboran_id' => '8'
            ],
            [
                'kode' => '15',
                'nama' => 'Lab. Higiene Industri',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '3',
                'laboran_id' => '8'
            ],
            [
                'kode' => '16',
                'nama' => 'Lab. Keselamatan Kerja',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '3',
                'laboran_id' => '8'
            ],
            [
                'kode' => '17',
                'nama' => 'Lab. TSF',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '4',
                'laboran_id' => '9'
            ],
            [
                'kode' => '18',
                'nama' => 'Lab. Bahan Alam',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '4',
                'laboran_id' => '10'
            ],
            [
                'kode' => '19',
                'nama' => 'Lab. Farmakologi',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '4',
                'laboran_id' => '11'
            ],
            [
                'kode' => '20',
                'nama' => 'Lab. Kimia',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '4',
                'laboran_id' => '12'
            ],
            [
                'kode' => '21',
                'nama' => 'Lab. Biologi',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '4',
                'laboran_id' => '13'
            ],
            [
                'kode' => '22',
                'nama' => 'Lab. Instrumen',
                'tempat_id' => '2',
                'lantai' => 'L1',
                'prodi_id' => '4',
                'laboran_id' => '14'
            ],
            [
                'kode' => '23',
                'nama' => 'Lab. Bayi Baru Lahir',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '15'
            ],
            [
                'kode' => '24',
                'nama' => 'Lab. Post Natal Care',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '15'
            ],
            [
                'kode' => '25',
                'nama' => 'Lab. Bayi Baru Lahir dan Anak Pra Sekolah',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '15'
            ],
            [
                'kode' => '26',
                'nama' => 'Lab. Konseling KB',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '15'
            ],
            [
                'kode' => '27',
                'nama' => 'Lab. OSCE 1',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '15'
            ],
            [
                'kode' => '28',
                'nama' => 'Lab. Intranatal Care',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '16'
            ],
            [
                'kode' => '29',
                'nama' => 'Lab. Antenatal Care',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '16'
            ],
            [
                'kode' => '30',
                'nama' => 'Lab. Konseling dan Pendidikan Kesehatan',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '16'
            ],
            [
                'kode' => '31',
                'nama' => 'Lab. Tumbang',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '16'
            ],
            [
                'kode' => '32',
                'nama' => 'Lab. OSCE 2',
                'tempat_id' => '1',
                'lantai' => 'L1',
                'prodi_id' => '1',
                'laboran_id' => '16'
            ],
        ];

        Ruang::insert($ruangs);

        //     [
        //         'nama' => 'K.D.K',
        //     ],
        //     [
        //         'nama' => 'Lab. KB',
        //     ],
        //     [
        //         'nama' => 'Lab. Gangrep Dan Gender',
        //     ],
        //     [
        //         'nama' => 'Lab. Jiwa',
        //     ],
        //     [
        //         'nama' => 'Lab. Anak',
        //     ],
        //     [
        //         'nama' => 'Lab. Instrumen',
        //     ],
        //     [
        //         'nama' => 'Lab. Anatomi',
        //     ],
        //     [
        //         'nama' => 'Lab. K3',
        //     ],
        //     [
        //         'nama' => 'Persalinan dan BBL',
        //     ],
        //     [
        //         'nama' => 'Pra Konsepsi dan Kehamilan',
        //     ],
        //     [
        //         'nama' => 'Nifas dan Menyusui',
        //     ],
        //     [
        //         'nama' => 'Neonatus, Bayi, Balita, dan Anak Bersekolah',
        //     ],
        //     [
        //         'nama' => 'Kegawatdaruratan',
        //     ],
        //     [
        //         'nama' => 'I.C.U.',
        //     ],
        //     [
        //         'nama' => 'Picu / Nicu',
        //     ],
        //     [
        //         'nama' => 'Ponek',
        //     ],
        //     [
        //         'nama' => 'Medikal Bedah',
        //     ],
        //     [
        //         'nama' => 'Kebutuhan Dsar Manusia (KDM)',
        //     ],
        //     [
        //         'nama' => 'Maternitas',
        //     ],
        //     [
        //         'nama' => 'Keluarga, Komunitas, dan Gerozik',
        //     ],
        //     [
        //         'nama' => 'Kimia Farmasi',
        //     ],
        //     [
        //         'nama' => 'Farmakologi Farmasi',
        //     ],
        //     [
        //         'nama' => 'Biologi Farmasi',
        //     ],
        //     [
        //         'nama' => 'Bahan Alam Farmasi',
        //     ],
        //     [
        //         'nama' => 'Farmasetika dan Teknologi Farmasi',
        //     ],
        //     [
        //         'nama' => 'Landasan Ilmiah Kebidanan',
        //     ],
        //     [
        //         'nama' => 'Remaja dan Pranikah',
        //     ],
        //     [
        //         'nama' => 'RUANG CENTRAL ALAT',
        //     ],
        //     [
        //         'nama' => 'Lab. Biomedik',
        //     ],
        // ];

        // Ruang::insert($ruangs);

        // $response = Http::get('http://127.0.0.1:8000/api/get-ruang');

        // if ($response['status'] == true) {
        //     $ruangs = $response['ruangs'];

        //     foreach ($ruangs as $ruang) {
        //         $id = array('1', '2', '3', '4', '5', '6');
        //         $prodi_id = array_rand($id, 1);

        //         Ruang::create([
        //             'nama' => $ruang['nama'],
        //             'prodi_id' => $id[$prodi_id],
        //         ]);
        //     }
        // }
    }
}
