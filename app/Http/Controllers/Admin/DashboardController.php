<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\PeminjamanTamu;
use App\Models\Tamu;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index()
    {
        $statusCounts = PeminjamanTamu::selectRaw('status, COUNT(*) as total')
            ->whereIn('status', ['proses', 'selesai', 'tagihan'])
            ->groupBy('status')
            ->pluck('total', 'status');
        // 
        $proses  = $statusCounts['proses'] ?? 0;
        $riwayat = $statusCounts['selesai'] ?? 0;
        $tagihan = $statusCounts['tagihan'] ?? 0;
        // 
        $roleCounts = User::selectRaw('role, COUNT(*) as total')
            ->whereIn('role', ['peminjam', 'laboran'])
            ->groupBy('role')
            ->pluck('total', 'role');
        // 
        $peminjam = $roleCounts['peminjam'] ?? 0;
        $laboran  = $roleCounts['laboran'] ?? 0;
        // 
        $tamu = Tamu::count();
        // 
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
        // 
        return $barang;
    }

    public function search_items(Request $request)
    {
        $keyword = $request->barang_keyword;
        $page = $request->barang_page;
        // 
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
            ->orderBy('nama')
            ->take($page)
            ->get();
        // 
        return $barangs;
    }

    public function tamu_set($id)
    {
        $tamu = Tamu::where('id', $id)
            ->select('id', 'nama')
            ->first();
        // 
        return $tamu;
    }

    public function search_tamus(Request $request)
    {
        $keyword = $request->tamu_keyword;
        $page = $request->tamu_page;
        // 
        $tamus = Tamu::when($keyword, function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                    ->orWhere('institusi', 'like', "%$keyword%");
            });
        })
            ->select('id', 'nama', 'institusi')
            ->orderBy('nama')
            ->take($page)
            ->get();
        // 
        return $tamus;
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

    public function form_peminjaman_lab()
    {
        $pdf = Pdf::loadview('admin.form_peminjaman_lab');
        return $pdf->stream('Form Peminjaman Laboratorium.pdf');
    }

    public function form_jurnal_praktikum()
    {
        $pdf = Pdf::loadview('admin.form_jurnal_praktikum');
        return $pdf->stream('Form Jurnal Praktikum.pdf');
    }

    public function form_rekap_jurnal()
    {
        $pdf = Pdf::loadview('admin.form_rekap_jurnal');
        return $pdf->stream('Form Rekap Jurnal.pdf');
    }
}
