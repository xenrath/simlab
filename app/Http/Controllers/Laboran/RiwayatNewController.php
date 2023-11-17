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
            ['kategori', 'normal'],
            ['status', 'selesai']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
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
            ->orderByDesc('id')
            ->paginate(6);

        return view('laboran.riwayat-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return redirect('laboran/riwayat-new/praktik-laboratorium/' . $id);
        } else if ($pinjam->praktik_id == 2) {
            return redirect('laboran/riwayat-new/praktik-kelas/' . $id);
        } else if ($pinjam->praktik_id == 3) {
            return redirect('laboran/riwayat-new/praktik-luar/' . $id);
        }
    }

    public function destroy($id)
    {
        Pinjam::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
    }
}
