<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanNewController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
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
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->get();

        return view('laboran.peminjaman-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->show_lab($id);
        } elseif ($praktik_id == 2) {
            return $this->show_kelas($id);
        } elseif ($praktik_id == 3) {
            return $this->show_luar($id);
        } elseif ($praktik_id == 4) {
            return $this->show_ruang($id);
        }
    }

    public function show_lab($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'bahan'
            )
            ->with('praktik:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
            })
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('laboran.peminjaman-new.show_lab', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'bahan'
            )
            ->with('praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('laboran.peminjaman-new.show_kelas', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'keterangan',
                'bahan'
            )
            ->with('praktik:id,nama', 'laboran:id,nama')
            ->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('laboran.peminjaman-new.show_luar', compact('pinjam', 'detail_pinjams'));
    }

    public function show_ruang($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'bahan'
            )
            ->with('praktik:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
            })
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('laboran.peminjaman-new.show_ruang', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function setujui($id)
    {
        Pinjam::where('id', $id)->update([
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

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $kelompok = Kelompok::where('pinjam_id', $id)->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        if ($kelompok) {
            $kelompok->delete();
        }
        if ($detail_pinjams) {
            foreach ($detail_pinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        $pinjam->forceDelete();

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return back();
    }
}
