<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;

class TagihanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('peminjam_id', auth()->user()->id)->whereHas('detail_pinjams', function ($query) {
            $query->where('rusak', '>', '0')->orWhere('hilang', '>', '0');
        })->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->whereHas('detail_pinjams', function ($query) {
            $query->where('rusak', '>', '0')->orWhere('hilang', '>', '0');
        })->get();

        return view('peminjam.tagihan.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $rusaks = DetailPinjam::where('pinjam_id', $pinjam->id)->where('rusak', '>', '0')->get();
        $hilangs = DetailPinjam::where('pinjam_id', $pinjam->id)->where('hilang', '>', '0')->get();

        return view('peminjam.tagihan.show', compact('pinjam', 'rusaks', 'hilangs'));
    }
}
