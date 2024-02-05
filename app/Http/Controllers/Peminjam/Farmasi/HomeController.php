<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $peminjaman = Pinjam::where([
            ['status', 'menunggu'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        $pengembalian = Pinjam::where([
            ['status', 'disetujui'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        $riwayat = Pinjam::where([
            ['status', 'selesai'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        $tagihan = Pinjam::where([
            ['status', 'tagihan'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        return view('peminjam.farmasi.index', compact(
            'peminjaman',
            'pengembalian',
            'riwayat',
            'tagihan',
        ));
    }
}
