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
        if (auth()->user()->ruangs->first()->tempat_id == '1') {
            return $this->index_lab_terpadu();
        } else if (auth()->user()->ruangs->first()->tempat_id == '2') {
            return $this->index_farmasi();
        };
    }

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
        $menunggu = Pinjam::where('status', 'menunggu')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();
        $proses = Pinjam::where('status', 'disetujui')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();
        $selesai = Pinjam::where('status', 'selesai')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();
        $tagihan = Pinjam::where('status', 'tagihan')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();

        return view('laboran.index', compact(
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
