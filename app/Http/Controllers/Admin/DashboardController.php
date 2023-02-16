<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\Ruang;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $kalabs = User::where('role', 'kalab')->get();
        $laborans = User::where('role', 'laboran')->get();
        $peminjams = User::where('role', 'peminjam')->get();

        $ruangs = Ruang::get();

        $barangs = Barang::get();
        $bahans = Bahan::get();

        return view('admin.index', compact('kalabs', 'laborans', 'peminjams', 'ruangs', 'barangs', 'bahans'));
    }
}
