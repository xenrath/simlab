<?php

namespace App\Http\Controllers\Laboran\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menunggu = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();

        $proses = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();

        $selesai = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'selesai']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();
            
        $tagihan = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'tagihan']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();

        return view('laboran.bidan.index', compact(
            'menunggu',
            'proses',
            'selesai',
            'tagihan'
        ));
    }
}
