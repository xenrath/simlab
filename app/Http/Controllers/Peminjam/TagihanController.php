<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $detailpinjams = DetailPinjam::where('rusak', '>', '0')->whereHas('pinjam', function ($query) {
            $query->whereHas('kelompoks', function ($query) {
                $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
            });
        })->get();

        // return response($detailpinjams);

        return view('peminjam.tagihan.index', compact('detailpinjams'));
    }

    public function show($id)
    {
        $detailpinjam = DetailPinjam::where('id', $id)->first();
        $pinjam = Pinjam::where('id', $detailpinjam->pinjam_id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();

        return view('peminjam.tagihan.show', compact('detailpinjam', 'pinjam', 'detailpinjams'));
    }
}
