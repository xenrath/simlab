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
use Jenssegers\Agent\Agent;

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

    public function hubungi($id)
    {
        $telp = User::where('id', $id)->value('telp');

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $telp);
        }
    }
}
