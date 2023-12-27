<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\Pinjam;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\SubProdi;
use App\Models\Tempat;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $peminjamans = Pinjam::count();
        $users = User::count();
        $prodis = Prodi::count();
        $sub_prodis = SubProdi::count();
        $tempats = Tempat::count();
        $ruangs = Ruang::count();
        $barangs = Barang::count();
        $bahans = Bahan::count();

        return view('dev.index', compact(
            'peminjamans',
            'users',
            'prodis',
            'sub_prodis',
            'tempats',
            'ruangs',
            'barangs',
            'bahans'
        ));
    }
}
