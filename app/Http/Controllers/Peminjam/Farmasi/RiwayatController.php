<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
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
                    $query->where('ketua', auth()->user()->kode)->whereJsonContains('anggota', auth()->user()->kode);
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
                'matakuliah',
                'kategori',
                'status'
            )
            ->with('praktik:id,nama', 'ruang:id,nama', 'peminjam:id,nama')
            ->orderByDesc('id')
            ->paginate(6);

        return view('peminjam.farmasi.riwayat.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select('kategori', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'selesai') {
            return redirect('peminjam/farmasi')->with('error', 'Peminjaman tidak ditemukan!');
        }

        switch ($pinjam->kategori) {
            case 'normal':
                return $this->show_mandiri($id);
            case 'estafet':
                return $this->show_estafet($id);
            default:
                abort(404, 'Jenis praktik tidak ditemukan.');
        }
    }

    public function show_mandiri($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'laboran:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'laboran_id', 'nama')->with('laboran:id,nama');
            })
            ->first();

        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah',
            )
            ->get();

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
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

        return view('peminjam.farmasi.riwayat.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'tagihan_peminjamans',
        ));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'jam_awal',
                'jam_akhir',
                'tanggal_awal',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'laboran:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'laboran_id', 'nama')->with('laboran:id,nama');
            })
            ->first();

        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah',
            )
            ->get();

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

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
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

        return view('peminjam.farmasi.riwayat.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'data_kelompok',
            'pinjam_detail_bahans',
            'tagihan_peminjamans',
        ));
    }
}
