<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class KelompokPeminjamanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'estafet'],
            ['status', 'menunggu']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        return view('laboran.kelompok.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        return view('laboran.kelompok.peminjaman.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
    }

    public function konfirmasi_setuju($id)
    {
        Pinjam::where('id', $id)->update([
            'status' => 'disetujui'
        ]);

        alert()->success('Success', 'Berhasil menyetujui Peminjaman');

        return redirect('laboran/kelompok/peminjaman');
    }
}
