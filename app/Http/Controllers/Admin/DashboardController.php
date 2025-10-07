<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\PeminjamanTamu;
use App\Models\Ruang;
use App\Models\Tamu;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
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

    public function search_items1(Request $request)
    {
        $keyword = $request->barang_keyword;
        $page = $request->barang_page ?? 10;

        // Langkah 1: cari ID barang yang cocok via Algolia
        $searchResults = Barang::search($keyword, function ($algolia, $query, $options) {
            $options['filters'] = 'ruang_tempat_id=1';
            return $algolia->search($query, $options);
        })->take($page)->get();

        // Ambil ID-nya
        $ids = $searchResults->pluck('id');

        // Langkah 2: ambil data lengkap dari DB berdasarkan ID, dengan relasi ruang
        $barangs = Barang::whereIn('id', $ids)
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->get();

        return response()->json($barangs);
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

    public function ruang_set($id)
    {
        $ruang = Ruang::where('id', $id)->select('id', 'nama')->first();

        return $ruang;
    }

    public function ruang_search(Request $request)
    {
        $keyword = $request->ruang_keyword;
        $page = $request->ruang_page;

        $ruangs = Ruang::select('id', 'nama', 'prodi_id')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%$keyword%");
                });
            })
            ->with(['prodi' => function ($query) {
                $query->select('id', 'nama', 'is_prodi')
                    ->where('is_prodi', true);
            }])
            ->orderBy('nama')
            ->take($page)
            ->get();

        return $ruangs;
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

    public function bahan_cari(Request $request)
    {
        $nama     = $request->bahan_nama;
        $prodi_id = $request->bahan_prodi_id;
        $limit    = (int) $request->input('bahan_page', 10);

        $bahans = Bahan::select('id', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->when($nama, function ($query) use ($nama) {
                $query->where('nama', 'like', "%{$nama}%");
            })
            ->when($prodi_id, function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })
            ->orderBy('nama')
            ->take($limit)
            ->get();

        return $bahans;
    }

    public function bahan_tambah($id)
    {
        $bahan = Bahan::where('id', $id)
            ->select(
                'id',
                'nama',
                'prodi_id',
                'satuan_pinjam',
            )
            ->with('prodi:id,nama')
            ->first();

        return $bahan;
    }
}
