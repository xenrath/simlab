<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class KelompokRiwayatController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'estafet'],
            ['status', 'selesai']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        return view('laboran.kelompok.riwayat.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        return view('laboran.kelompok.riwayat.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
    }
}
