<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pinjam;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GrafikController extends Controller
{
    public function pengunjung()
    {
        $labels = array("September", "Oktober", "November", "Desember", "Januari", "Februari");
        $data = array("102", "133", "196", "187", "31", "42");

        return view('kalab.grafik.pengunjung', compact('labels', 'data'));
    }

    public function ruang()
    {
        $ruangs = Ruang::selectRaw('nama')->withCount('pinjams')->orderByDesc('pinjams_count')->limit(10)->get();

        $labels = $ruangs->pluck('nama');
        $data = $ruangs->pluck('pinjams_count');

        return view('kalab.grafik.ruang', compact('labels', 'data'));
    }

    public function barang()
    {
        $barangs = Barang::selectRaw('nama')->withCount('detailpinjams')->orderByDesc('detailpinjams_count')->limit(10)->get();

        // return response($barangs);

        $labels = $barangs->pluck('nama');
        $data = $barangs->pluck('detailpinjams_count');

        return view('kalab.grafik.barang', compact('labels', 'data'));
    }
}
