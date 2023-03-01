<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;

class RiwayatNewController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'selesai'],
            ['peminjam_id', auth()->user()->id],
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where([
            ['kategori', 'normal'],
            ['status', 'selesai']
        ])->get();

        return view('peminjam.riwayat-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('peminjam.riwayat-new.show', compact('pinjam', 'detailpinjams'));
    }
}
