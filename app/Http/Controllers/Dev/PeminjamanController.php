<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        if ($status != "") {
            $pinjams = Pinjam::select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
                'kategori',
                'status'
            )
                ->where('status', $status)
                ->with('peminjam:id,kode,nama,subprodi_id', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
                ->orderByDesc('id')
                ->paginate(10);
        } else {
            $pinjams = Pinjam::select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
                'kategori',
                'status'
            )
                ->with('peminjam:id,kode,nama,subprodi_id', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
                ->orderByDesc('id')
                ->paginate(10);
        }

        return view('dev.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();

        return view('dev.peminjaman.show', compact('pinjam', 'detailpinjams'));
    }

    public function hapus_draft()
    {
        $pinjams = Pinjam::where('status', 'draft')->with('kelompoks', 'detail_pinjams')->withTrashed()->get();

        foreach ($pinjams as $pinjam) {
            $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();
            $detail_pinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();

            if ($kelompoks) {
                foreach ($kelompoks as $kelompok) {
                    $kelompok->delete();
                }
            }

            if ($detail_pinjams) {
                foreach ($detail_pinjams as $detailpinjam) {
                    $detailpinjam->delete();
                }
            }

            $pinjam->forceDelete();
        }

        alert()->success('Success', 'Berhasil menghapus Draft Peminjaman');

        return back();
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->delete();

        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }

        if ($detailpinjams) {
            if ($pinjam->status != 'selesai') {
                foreach ($detailpinjams as $detailpinjam) {
                    $barang = Barang::where('id', $detailpinjam->barang_id)->first();
                    $barang->update([
                        'normal' => $barang->normal + $detailpinjam->jumlah
                    ]);
                }
            }
            foreach ($detailpinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('dev/peminjaman');
    }
}
