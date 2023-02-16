<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'kode' => 'dev',
                'username' => 'dev',
                'nama' => 'Dev',
                'password' => bcrypt('dev'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'dev'
            ],
            [
                'kode' => 'admin',
                'username' => 'admin',
                'nama' => 'Admin',
                'password' => bcrypt('admin'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'admin'
            ],
            [
                'kode' => 'kalab',
                'username' => 'kalab',
                'nama' => 'Ika Esti Anggraeni, SST, M.Kes',
                'password' => bcrypt('kalab'),
                'telp' => '81234567891',
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'kalab'
            ],
            [
                'kode' => 'untung',
                'username' => 'untung',
                'nama' => 'Untung Purbowasesa, S.Kep.Ns',
                'password' => bcrypt('untung'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'devva',
                'username' => 'devva',
                'nama' => 'Devva Saptia Maharani, S.Kep',
                'password' => bcrypt('devva'),
                'telp' => null,
                'gender' => 'P',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'maulana',
                'username' => 'maulana',
                'nama' => 'Maulana Aenul Yakin, S.Kep',
                'password' => bcrypt('maulana'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'putri',
                'username' => 'putri',
                'nama' => 'Putri Aprilia Khoirun Nisa, S.Kep',
                'password' => bcrypt('putri'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'subekti',
                'username' => 'subekti',
                'nama' => 'Subekti Sulistiyani, SKM',
                'password' => bcrypt('subekti'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'sudiono',
                'username' => 'sudiono',
                'nama' => 'Sudiono, A.Md.Farm',
                'password' => bcrypt('sudiono'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'kaka',
                'username' => 'kaka',
                'nama' => 'Kaka Uki Azkiya, A.Md.Farm',
                'password' => bcrypt('kaka'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'eti',
                'username' => 'eti',
                'nama' => 'Eti Purwatih, S.Farm',
                'password' => bcrypt('eti'),
                'telp' => null,
                'gender' => 'P',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'desi1',
                'username' => 'desi1',
                'nama' => 'Desi Purnama Sari, S.Si',
                'password' => bcrypt('desi1'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'shofa',
                'username' => 'shofa',
                'nama' => 'apt. Shofa Khoirun Nida, S.Farm',
                'password' => bcrypt('shofa'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'aditya',
                'username' => 'aditya',
                'nama' => 'apt. Aditya Yulindra A.P, S.Farm',
                'password' => bcrypt('aditya'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'evi',
                'username' => 'evi',
                'nama' => 'Evi Dwi Mulyanti, S.ST',
                'password' => bcrypt('evi'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'desi2',
                'username' => 'desi2',
                'nama' => 'Desi Widiyastuti, S.Tr.Keb',
                'password' => bcrypt('desi2'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'laboran'
            ],
            [
                'kode' => 'web',
                'username' => 'web',
                'nama' => 'Admin Website',
                'password' => bcrypt('web'),
                'telp' => null,
                'gender' => 'L',
                'alamat' => null,
                'subprodi_id' => null,
                'semester' => null,
                'role' => 'web'
            ],
        ];

        User::insert($users);

        // $response = Http::get('http://127.0.0.1:8000/api/get-user');

        // if ($response['status'] == true) {
        //     $users = $response['users'];
        //     foreach ($users as $user) {
        //         $role = array('laboran', 'peminjam');

        //         $role_random = array_rand($role, 1);
        //         $rolex = $role[$role_random];

        //         $semester = array('1', '3', '5');
        //         $smt_rand = array_rand($semester, 1);

        //         if ($rolex == "peminjam") {
        //             $prodi = array('1', '2', '3', '4', '5', '6');
        //             $prodi_random = array_rand($prodi, 1);
        //             $prodix = $prodi[$prodi_random];
        //             $smtx = $semester[$smt_rand];
        //         } else {
        //             $prodix = null;
        //             $smtx = null;
        //         }

        //         User::create([
        //             'kode' => $user['name'],
        //             'username' => $user['name'],
        //             'nama' => $user['nama_lengkap'],
        //             'password' => bcrypt($user['name']),
        //             'telp' => $user['tlp'],
        //             'gender' => 'P',
        //             'alamat' => $user['alamat'],
        //             'subprodi_id' => $prodix,
        //             'semester' => $smtx,
        //             'role' => $rolex
        //         ]);
        //     }
        // }
    }
}
