<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Ruang;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class BarangSeeder extends Seeder
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

                $kategori = array('barang', 'bahan');
                $random = array_rand($kategori, 1);

                $sat = array('1', '2', '3', '4', '5');
                $ran = array_rand($sat, 1);

                $ruang = array('1', '2', '3', '4');
                $ran_ruang = array_rand($ruang, 1);

                $barang = Barang::create([
                    'kode' => $this->generateCode($ruang[$ran_ruang]),
                    'nama' => $barang['nama_barang'],
                    'ruang_id' => $ruang[$ran_ruang],
                    'normal' => '10',
                    'rusak' => '2',
                    'total' => '12',
                    'satuan_id' => '6',
                    'keterangan' => $barang['keterangan'],
                    'gambar' => null,
                ]);
                StokBarang::create([
                    'barang_id' => $barang->id,
                    'normal' => '10',
                    'rusak' => '2',
                    'satuan_id' => '6',
                    'created_at' => Carbon::now()->addDay($num[$day])
                ]);
            }
        }
    }

    public function generateCode($ruang_id)
    {
        // $barangs = Barang::where('ruang_id', $ruang_id)->get();
        // if (count($barangs) > 0) {
        //     $jumlah = count($barangs) + 1;
        //     $urutan = sprintf('%03s', $jumlah);
        // } else {
        //     $urutan = "001";
        // }

        // $kode = $kode . $urutan;
        // return $kode;

        $barangs = Barang::where('ruang_id', $ruang_id)->get();
        $barang = Barang::where('ruang_id', $ruang_id)->orderByDesc('kode')->first();
        $ruang = Ruang::where('id', $ruang_id)->first();
        if (count($barangs) > 0) {
            $last = substr($barang->kode, 15);
            $jumlah = (int)$last + 1;
            $urutan = sprintf('%03s', $jumlah);
        } else {
            $urutan = "001";
        }

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".01." . $urutan;
        return $kode;
    }
}
