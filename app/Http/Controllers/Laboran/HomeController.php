<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{

    public function index()
    {
        if (auth()->user()->isBidan()) {
            return redirect('laboran/bidan');
        } elseif (auth()->user()->isPerawat()) {
            return redirect('laboran/perawat');
        } elseif (auth()->user()->isK3()) {
            return redirect('laboran/k3');
        } elseif (auth()->user()->isFarmasi()) {
            return redirect('laboran/farmasi');
        }
    }

    // public function index()
    // {
    //     if (auth()->user()->ruangs->first()->tempat_id == '1') {
    //         return $this->index_lab_terpadu();
    //     } else if (auth()->user()->ruangs->first()->tempat_id == '2') {
    //         return $this->index_farmasi();
    //     };
    // }

    public function index_lab_terpadu()
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

        return view('laboran.index_lab_terpadu', compact(
            'menunggu',
            'proses',
            'selesai',
            'tagihan'
        ));
    }

    public function index_farmasi()
    {
        return view('laboran.index');
    }

    public function hubungi($id)
    {
        $telp = User::where('id', '0')->value('telp');

        if (empty($telp)) {
            return back()->with('error', 'Nomor telepon tidak tersedia!');
        }

        $agent = new Agent;
        $baseUrl = $agent->isDesktop()
            ? 'https://web.whatsapp.com/send?phone='
            : 'https://wa.me/';

        return redirect()->away($baseUrl . '+62' . ltrim($telp, '0'));
    }

    public function get_barang(Request $request)
    {
        $items = $request->items;
        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan')->orderBy('kategori', 'DESC')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }
}
