<?php

namespace App\Http\Controllers\Peminjam\Bidan;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use App\Models\User;

class TagihanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('status', 'tagihan')
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

        return view('peminjam.bidan.tagihan.index', compact('pinjams', 'total'));
    }

    public function show($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->show_lab($id);
        } else if ($praktik_id == 2) {
            return $this->show_kelas($id);
        } else if ($praktik_id == 3) {
            return $this->show_luar($id);
        }
    }

    public function show_lab($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.bahan'
            )
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
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.jumlah',
                'barangs.nama',
                'tagihan_peminjamans.created_at'
            )
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.detail_pinjam_id',
                'tagihan_peminjamans.jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');
        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('peminjam.bidan.tagihan.show_lab', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
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
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.jumlah',
                'barangs.nama',
                'tagihan_peminjamans.created_at'
            )
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.detail_pinjam_id',
                'tagihan_peminjamans.jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');
        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('peminjam.bidan.tagihan.show_kelas', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id',  '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.jumlah',
                'barangs.nama',
                'tagihan_peminjamans.created_at'
            )
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.detail_pinjam_id',
                'tagihan_peminjamans.jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');
        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('peminjam.bidan.tagihan.show_luar', compact(
            'pinjam',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }
}
