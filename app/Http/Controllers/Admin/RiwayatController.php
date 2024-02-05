<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use App\Models\TagihanPeminjamanTamu;

class RiwayatController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('status', 'selesai')
            ->select(
                'id',
                'tamu_id',
                'tanggal_awal',
                'tanggal_akhir',
                'keperluan',
            )
            ->with('tamu:id,nama,institusi')
            ->paginate(10);

        return view('admin.riwayat.index', compact('peminjaman_tamus'));
    }

    public function show($id)
    {
        $peminjaman_tamu = PeminjamanTamu::where('id', $id)
            ->select(
                'id',
                'tamu_id',
                'lama',
                'keperluan',
                'tanggal_awal',
                'tanggal_akhir',
            )
            ->with('tamu:id,nama,telp,institusi,alamat')
            ->first();
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select(
                'barang_id',
                'total',
            )
            ->with('barang', function ($query) {
                $query->select(
                    'id',
                    'nama',
                    'ruang_id'
                )->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjaman_tamus = TagihanPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select(
                'id',
                'detail_peminjaman_tamu_id',
                'jumlah',
                'created_at',
            )
            ->with('detail_peminjaman_tamu', function ($query) {
                $query->select('id', 'barang_id')->with('barang:id,nama');
            })
            ->get();

        return view('admin.riwayat.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus', 'tagihan_peminjaman_tamus'));
    }
}
