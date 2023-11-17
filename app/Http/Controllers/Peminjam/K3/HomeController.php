<?php

namespace App\Http\Controllers\Peminjam\K3;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menunggu = Pinjam::where('status', 'menunggu')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->count();
        $proses = Pinjam::where('status', 'disetujui')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->count();
        $riwayat = Pinjam::where('status', 'selesai')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->count();
        $tagihan = Pinjam::where('status', 'tagihan')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->count();

        return view('peminjam.k3.index', compact('menunggu', 'proses', 'riwayat', 'tagihan'));
    }
}
