<?php

namespace App\Http\Controllers\Peminjam\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Praktik;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['peminjam_id', auth()->user()->id],
        ])->orWhere([
            ['kategori', 'normal'],
        ])->whereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })
            ->select('id', 'praktik_id', 'ruang_id', 'tanggal_awal', 'tanggal_akhir', 'keterangan', 'status')
            ->with('praktik:id,nama', 'ruang:id,nama', 'peminjam:id,nama')
            ->orderByDesc('id')
            ->get();

        $praktiks = Praktik::select('id', 'nama')->get();

        return view('peminjam.peminjaman-new.index', compact('pinjams', 'praktiks'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return redirect('peminjam/normal/peminjaman-new/laboratorium/' . $id);
        } else if ($pinjam->praktik_id == 2) {
            return redirect('peminjam/normal/peminjaman-new/kelas/' . $id);
        } else if ($pinjam->praktik_id == 3) {
            return redirect('peminjam/normal/peminjaman-new/luar/' . $id);
        }
    }

    public function edit($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return redirect('peminjam/normal/peminjaman-new/laboratorium/' . $id . '/edit');
        } else if ($pinjam->praktik_id == 2) {
            return redirect('peminjam/normal/peminjaman-new/kelas/' . $id . '/edit');
        } else if ($pinjam->praktik_id == 3) {
            return redirect('peminjam/normal/peminjaman-new/luar/' . $id . '/edit');
        }
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->forceDelete();
        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }
        if ($detailpinjams) {
            foreach ($detailpinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('peminjam/normal/peminjaman-new');
    }

    // Cek Praktik
    public function praktik(Request $request)
    {
        $praktik_id = $request->praktik_id;

        if ($praktik_id == '1') {
            return redirect('peminjam/normal/peminjaman-new/laboratorium');
        } elseif ($praktik_id == '2') {
            return redirect('peminjam/normal/peminjaman-new/kelas');
        } elseif ($praktik_id == '3') {
            return redirect('peminjam/normal/peminjaman-new/luar');
        } else {
            alert()->error('Gagal!', 'Kategori praktik belum dipilih!');
            return back();
        }
    }
}
