<?php

namespace App\Http\Controllers\Peminjam\Perawat;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use App\Models\User;

class RiwayatController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('status', 'selesai')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
                'kategori',
                'status'
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->simplePaginate(6);
        $total = Pinjam::where('status', 'selesai')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })->count();

        return view('peminjam.perawat.riwayat.index', compact('pinjams', 'total'));
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
            ->with('praktik:id,nama', 'laboran:id,nama')
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
            ->select(
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id');
                    $query->with('ruang:id,nama');
                });
            })
            ->get();

        return view('peminjam.perawat.riwayat.show_lab', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans'
        ));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'laboran_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'keterangan',
                'bahan'
            )
            ->with('praktik:id,nama', 'laboran:id,nama')
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
            ->select(
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id');
                    $query->with('ruang:id,nama');
                });
            })
            ->get();

        return view('peminjam.perawat.riwayat.show_kelas', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
        ));
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
            ->select(
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id');
                    $query->with('ruang:id,nama');
                });
            })
            ->get();

        return view('peminjam.perawat.riwayat.show_luar', compact(
            'pinjam',
            'detail_pinjams',
            'tagihan_peminjamans',
        ));
    }

    public function show_ruang($id)
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
            ->with('praktik:id,nama', 'laboran:id,nama')
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

        return view('peminjam.perawat.riwayat.show_ruang', compact('pinjam', 'data_kelompok'));
    }
}
