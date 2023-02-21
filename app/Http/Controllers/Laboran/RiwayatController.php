<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if ($keyword != "") {
            $pinjams = Pinjam::whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderBy('updated_at', 'DESC')->orderBy('jam_awal', 'DESC')
                ->where([
                    ['kategori', 'normal'],
                    ['status', '!=', 'menunggu'],
                    ['status', '!=', 'disetujui']
                ])
                ->whereHas('peminjam', function ($query) use ($keyword) {
                    $query->where('nama', 'LIKE', "%$keyword%");
                })->paginate(10);
        } else {
            $pinjams = Pinjam::whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderBy('updated_at', 'DESC')->orderBy('jam_awal', 'DESC')->where([
                ['kategori', 'normal'],
                ['status', '!=', 'menunggu'],
                ['status', '!=', 'disetujui']
            ])->paginate(10);
        }

        return view('laboran.riwayat.index', compact('pinjams'));
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

        return view('laboran.riwayat.show', compact('pinjam', 'detailpinjams'));
    }

    public function destroy($id)
    {
        Pinjam::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
    }
}
