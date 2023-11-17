<?php

namespace App\Http\Controllers\Laboran\Tagihan;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use Illuminate\Http\Request;

class LuarController extends Controller
{
    public function show($id)
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
        $detail_pinjams = DetailPinjam::where([
            ['detail_pinjams.pinjam_id', $id],
            ['detail_pinjams.status', 0]
        ])
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.jumlah',
                'detail_pinjams.rusak',
                'detail_pinjams.hilang',
                'barangs.nama as barang_nama',
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

        return view('laboran.tagihan.luar.show', compact(
            'pinjam', 
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }
}
