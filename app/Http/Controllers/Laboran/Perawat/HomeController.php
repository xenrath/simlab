<?php

namespace App\Http\Controllers\Laboran\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use App\Models\User;
use Jenssegers\Agent\Agent;

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

        return view('laboran.perawat.index', compact(
            'menunggu',
            'proses',
            'selesai',
            'tagihan'
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
