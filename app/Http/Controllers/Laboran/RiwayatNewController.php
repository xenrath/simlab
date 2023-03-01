<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class RiwayatNewController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orderBy('tanggal_awal', 'DESC')->orderBy('jam_awal', 'DESC')->get();

        return view('laboran.riwayat-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'ASC');
        })->get();

        return view('laboran.riwayat-new.show', compact('pinjam', 'detailpinjams'));
    }

    public function destroy($id)
    {
        Pinjam::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
    }
}
