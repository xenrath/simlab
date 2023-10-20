<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;

class SelesaiController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('peminjaman_tamus.status', 'selesai')
            ->join('tamus', 'peminjaman_tamus.tamu_id', '=', 'tamus.id')->select(
                'peminjaman_tamus.id',
                'peminjaman_tamus.tanggal_awal',
                'peminjaman_tamus.tanggal_akhir',
                'peminjaman_tamus.keperluan',
                'peminjaman_tamus.status',
                'tamus.nama as tamu_nama',
                'tamus.institusi as tamu_institusi'
            )
            ->get();

        return view('admin.peminjaman.selesai.index', compact('peminjaman_tamus'));
    }

    public function show($id)
    {
        $peminjaman_tamu = PeminjamanTamu::where('peminjaman_tamus.id', $id)
            ->join('tamus', 'peminjaman_tamus.tamu_id', '=', 'tamus.id')
            ->select(
                'peminjaman_tamus.lama',
                'peminjaman_tamus.keperluan',
                'peminjaman_tamus.tanggal_awal',
                'peminjaman_tamus.tanggal_akhir',
                'tamus.nama as tamu_nama',
                'tamus.telp as tamu_telp',
                'tamus.institusi as tamu_institusi',
                'tamus.alamat as tamu_alamat'
            )
            ->first();

        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->join('barangs', 'detail_peminjaman_tamus.barang_id', '=', 'barangs.id')
            ->select(
                'detail_peminjaman_tamus.total',
                'barangs.nama'
            )
            ->get();

        return view('admin.peminjaman.selesai.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
    }
}
