<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where('status', 'selesai')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->select(
                'id',
                'praktik_id',
                'peminjam_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'kategori',
            )
            ->with('praktik:id,nama', 'peminjam:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->get();

        return view('laboran.riwayat.index', compact('pinjams'));
    }

    public function show($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

        if ($kategori == 'normal') {
            return $this->show_mandiri($id);
        } elseif ($kategori == 'estafet') {
            return $this->show_estafet($id);
        }
    }

    public function show_mandiri($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'selesai'],
        ])
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'kategori',
                'status'
            )
            ->first();

        if (!$pinjam) {
            abort(404);
        }

        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah'
            )
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

        return view('laboran.riwayat.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'selesai'],
        ])
            ->select(
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'jam_awal',
                'jam_akhir',
                'tanggal_awal',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
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

        return view('laboran.riwayat.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'data_kelompok',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function destroy($id)
    {
        Pinjam::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
    }
}
