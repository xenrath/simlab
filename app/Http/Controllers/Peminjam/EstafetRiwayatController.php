<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class EstafetRiwayatController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'estafet'],
            ['status', 'selesai'],
            ['peminjam_id', auth()->user()->id]
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where([
            ['kategori', 'estafet'],
            ['status', 'selesai']
        ])->get();

        return view('peminjam.estafet.riwayat.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        return view('peminjam.estafet.riwayat.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
    }
}
