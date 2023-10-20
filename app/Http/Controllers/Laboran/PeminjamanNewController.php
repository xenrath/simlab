<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class PeminjamanNewController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.praktik_id',
                'pinjams.ruang_id',
                'users.nama as user_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.keterangan',
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->get();

        return view('laboran.peminjaman-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return redirect('laboran/peminjaman-new/praktik-laboratorium/' . $id);
        } else if ($pinjam->praktik_id == 2) {
            return redirect('laboran/peminjaman-new/praktik-kelas/' . $id);
        } else if ($pinjam->praktik_id == 3) {
            return redirect('laboran/peminjaman-new/praktik-luar/' . $id);
        }
    }

    public function setujui($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $pinjam->update([
            'status' => 'disetujui',
        ]);

        alert()->success('Success', 'Berhasil menyetujui Peminjaman');

        return redirect('laboran/peminjaman-new');
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

        return redirect('laboran/peminjaman-new');
    }
}
