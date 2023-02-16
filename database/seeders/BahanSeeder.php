<?php

namespace Database\Seeders;

use App\Models\Bahan;
use App\Models\Ruang;
use App\Models\StokBahan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class BahanSeeder extends Seeder
{
    public function run()
    {
        // $users = [
        //     [
        //         'kode' => '001',
        //         'nama' => 'Meja',
        //         'keterangan' => 'Kayu',
        //         'stok' => '10',
        //         'gambar' => '',
        //         'kategori' => 'barang',
        //         'kode_prodi' => 'BD',
        //     ],
        //     [
        //         'kode' => '002',
        //         'nama' => 'Kursi',
        //         'keterangan' => 'Kayu',
        //         'stok' => '10',
        //         'gambar' => '',
        //         'kategori' => 'barang',
        //         'kode_prodi' => 'PW',
        //     ],
        //     [
        //         'kode' => '003',
        //         'nama' => 'Bantal',
        //         'keterangan' => 'Busa',
        //         'stok' => '10',
        //         'gambar' => '',
        //         'kategori' => 'bahan',
        //         'kode_prodi' => 'FM',
        //     ],
        //     [
        //         'kode' => '004',
        //         'nama' => 'Sendok',
        //         'keterangan' => 'Besi',
        //         'stok' => '10',
        //         'gambar' => '',
        //         'kategori' => 'bahan',
        //         'kode_prodi' => 'K3',
        //     ],
        // ];

        // Barang::insert($users);

        $response = Http::get('http://127.0.0.1:8000/api/get-barang');

        if ($response['status'] == true) {
            $barangs = $response['barangs'];
            foreach ($barangs as $barang) {
                $num = array('-1', '-2', '-3', '-4', '-5', '-6', '-7', '-8', '-9', '-10');
                $day = array_rand($num, 1);

                $sat = array('1', '2', '3', '4', '5');
                $ran = array_rand($sat, 1);

                $ruang = array('1', '2', '3', '4');
                $ran_ruang = array_rand($ruang, 1);

                if ($ruang[$ran_ruang] == '1') {
                    $kode = "BD";
                } elseif ($ruang[$ran_ruang] == '2') {
                    $kode = "PW";
                } elseif ($ruang[$ran_ruang] == '3') {
                    $kode = "K3";
                } else {
                    $kode = "FM";
                }
                
                $bahan = Bahan::create([
                    'kode' => $this->generateCode($kode, $ruang[$ran_ruang]),
                    'nama' => $barang['nama_barang'],
                    'ruang_id' => $ruang[$ran_ruang],
                    'stok' => '10',
                    'satuan_id' => $sat[$ran],
                    'keterangan' => $barang['keterangan'],
                    'gambar' => null,
                ]);

                StokBahan::create([
                    'bahan_id' => $bahan->id,
                    'stok' => '10',
                    'satuan_id' => $sat[$ran],
                    'created_at' => Carbon::now()->addDay($num[$day])
                ]);
            }
        }
    }

    public function generateCode($kode, $ruang_id)
    {
        // $bahans = Bahan::where('ruang_id', $ruang_id)->get();
        // if (count($bahans) > 0) {
        //     $jumlah = count($bahans) + 1;
        //     $urutan = sprintf('%03s', $jumlah);
        // } else {
        //     $urutan = "001";
        // }

        // $kode = $kode . $urutan;
        // return $kode;

        $bahans = Bahan::where('ruang_id', $ruang_id)->get();
        $bahan = Bahan::where('ruang_id', $ruang_id)->orderByDesc('kode')->first();
        $ruang = Ruang::where('id', $ruang_id)->first();
        if (count($bahans) > 0) {
            $last = substr($bahan->kode, 15);
            $jumlah = (int)$last + 1;
            $urutan = sprintf('%03s', $jumlah);
        } else {
            $urutan = "001";
        }

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".02." . $urutan;
        return $kode;
    }
}
