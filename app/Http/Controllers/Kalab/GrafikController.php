<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Barang;
use App\Models\Ruang;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GrafikController extends Controller
{
    public function pengunjung()
    {
        $labels = array("September", "Oktober", "November", "Desember", "Januari", "Februari");
        $data = array("102", "133", "196", "187", "31", "42");

        $period = CarbonPeriod::create(today()->subMonths(6), '1 month', today()->subMonth());

        $dates = array();
        $labels = array();

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m');
            $labels[] = date('M Y', strtotime($date));
        }

        $data = array();

        foreach ($dates as $key => $date) {
            $absens = Absen::whereMonth('created_at', date('m', strtotime($date)))->whereYear('created_at', date('Y', strtotime($date)))->get();
            $data[] = count($absens);
        }

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
