<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanTamu;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index()
    {
        $proses = PeminjamanTamu::where('status', 'proses')->count();
        $riwayat = PeminjamanTamu::where('status', 'selesai')->count();
        $tagihan = PeminjamanTamu::where('status', 'tagihan')->count();

        $peminjam = User::where('role', 'peminjam')->count();
        $laboran = User::where('role', 'laboran')->count();
        $tamu = Tamu::count();

        return view('admin.index', compact(
            'proses',
            'riwayat',
            'tagihan',
            'peminjam',
            'laboran',
            'tamu',
        ));
    }

    public function add_item($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->first();
        return $barang;
    }

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('nama', 'like', "%$keyword%")
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->take(10)
            ->get();
        return $barangs;
    }

    public function hubungi_tamu($id)
    {
        $telp = Tamu::where('id', $id)->value('telp');

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $telp);
        }
    }

    public function hubungi_user($id)
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
