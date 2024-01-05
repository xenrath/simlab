<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
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
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as keterangan_praktik',
                'dosen',
                'kelas',
                'keterangan',
                'bahan',
                'kategori',
                'status'
            )
            ->with(
                'peminjam:id,nama,subprodi_id',
                'praktik:id,nama',
                'laboran:id,nama'
            )
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
            })
            ->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->get();
        $data_kelompok = array();

        foreach ($kelompoks as $kelompok) {
            $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
            $anggota = array();
            foreach ($kelompok->anggota as $kode) {
                $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
                array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
            }
            $data_kelompok[] = array(
                'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
                'anggota' => $anggota
            );
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('dev.peminjaman.show', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
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
