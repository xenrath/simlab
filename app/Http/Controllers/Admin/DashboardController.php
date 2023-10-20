<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $peminjam = User::where('role', 'peminjam')->count();
        $barang = Barang::count();
        $bahan = Bahan::count();
        
        return view('admin.index', compact('peminjam', 'barang', 'bahan'));
    }
}
