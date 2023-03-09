<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if ($keyword != "") {
            $pinjams = Pinjam::where([
                ['kategori', 'normal'],
                ['status', 'menunggu']
            ])->whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->whereHas('peminjam', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })->paginate(10);
        } else {
            $pinjams = Pinjam::where([
                ['kategori', 'normal'],
                ['status', 'menunggu']
            ])->whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->paginate(10);
        }

        return view('laboran.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->where([
            ['id', $id],
            ['status', 'menunggu']
        ])->first();

        if (!$pinjam) {
            abort(404);
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'ASC');
        })->get();

        return view('laboran.peminjaman.show', compact('pinjam', 'detail_pinjams'));
    }

    public function setujui($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $pinjam->update([
            'status' => 'disetujui',
        ]);

        alert()->success('Success', 'Berhasil menyetujui peminjaman');

        return redirect('laboran/peminjaman');
    }

    public function tolak($id)
    {
        $pinjam = Pinjam::whereHas('ruang', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('id', $id)->first();

        if (!$pinjam) {
            abort(404);
        }

        $tolak = $pinjam->update([
            'status' => 'ditolak',
            'laboran_id' => auth()->user()->id
        ]);

        if ($tolak) {
            alert()->success('Success', 'Berhasil menolak peminjaman');
        } else {
            alert()->error('Error!', 'Berhasil menolak peminjaman');
        }

        return redirect('laboran/peminjaman');
    }
}
