<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['status', 'selesai']
        ])->orWhere('status', 'selesai')->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        // return response($pinjams);

        return view('laboran.laporan.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->where([
            ['id', $id],
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui']
        ])->first();

        if (!$pinjam) {
            abort(404);
        }

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'ASC');
        })->get();

        return view('laboran.laporan.show', compact('pinjam', 'detailpinjams'));
    }

    public function print()
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['status', 'selesai']
        ])->orWhere('status', 'selesai')->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orderBy('id', 'desc')->get();

        $pdf = Pdf::loadview('laboran.laporan.print', compact('pinjams'));

        return $pdf->stream('cetak_laporan');
    }
}
