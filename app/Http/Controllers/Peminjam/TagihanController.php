<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;

class TagihanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('status', 'tagihan')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
            )
            ->with('praktik:id,nama', 'ruang:id,nama', 'peminjam:id,nama')
            ->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->get();

        // return $pinjams;

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
