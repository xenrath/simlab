<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use App\Models\TagihanPeminjamanTamu;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('status', 'tagihan')
            ->select(
                'id',
                'tamu_id',
                'tanggal_awal',
                'tanggal_akhir',
                'keperluan',
            )
            ->with('tamu:id,nama,institusi')
            ->get();

        return view('admin.tagihan.index', compact('peminjaman_tamus'));
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
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where([
            ['peminjaman_tamu_id', $id],
            ['status', 0]
        ])
            ->select(
                'id',
                'barang_id',
                'total',
                'rusak',
                'hilang',
            )
            ->with('barang:id,nama')
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
        $tagihan_group_by = TagihanPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select(
                'id',
                'detail_peminjaman_tamu_id',
                'jumlah'
            )
            ->get()
            ->groupBy('detail_peminjaman_tamu_id');

        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('admin.tagihan.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus', 'tagihan_peminjaman_tamus', 'tagihan_detail'));
    }

    public function update(Request $request, $id)
    {
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where([
            ['peminjaman_tamu_id', $id],
            ['status', 0]
        ])
            ->select(
                'id',
                'barang_id',
                'total',
                'rusak',
                'hilang',
            )
            ->with('barang:id,normal')
            ->get();

        $tagihan = 0;

        foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu) {

            $jumlah = $request->jumlah[$detail_peminjaman_tamu->id];

            if ($jumlah > 0) {

                $tagihan_jumlah = 0;

                $tagihan_peminjaman_tamu = TagihanPeminjamanTamu::where([
                    ['peminjaman_tamu_id', $id],
                    ['detail_peminjaman_tamu_id', $detail_peminjaman_tamu->id]
                ])->get('jumlah');

                if (count($tagihan_peminjaman_tamu)) {
                    foreach ($tagihan_peminjaman_tamu as $t) {
                        $tagihan_jumlah += $t->jumlah;
                    }
                }

                TagihanPeminjamanTamu::create([
                    'peminjaman_tamu_id' => $id,
                    'detail_peminjaman_tamu_id' => $detail_peminjaman_tamu->id,
                    'jumlah' => $jumlah
                ]);

                $rusak_hilang = $detail_peminjaman_tamu->rusak + $detail_peminjaman_tamu->hilang - $tagihan_jumlah;

                if ($jumlah != $rusak_hilang) {
                    $tagihan += 1;
                    $detail_peminjaman_tamu_status = false;
                } else {
                    $detail_peminjaman_tamu_status = true;
                }

                DetailPeminjamanTamu::where('id', $detail_peminjaman_tamu->id)->update([
                    'status' => $detail_peminjaman_tamu_status
                ]);

                Barang::where('id', $detail_peminjaman_tamu->barang_id)->update([
                    'normal' => $detail_peminjaman_tamu->barang->normal + $rusak_hilang,
                ]);
            } else {
                $tagihan += 1;
            }
        }

        if ($tagihan > 0) {
            $peminjaman_tamu_status = 'tagihan';
        } else {
            $peminjaman_tamu_status = 'selesai';
        }

        $peminjaman_tamu = PeminjamanTamu::where('id', $id)->update([
            'status' => $peminjaman_tamu_status
        ]);

        if ($peminjaman_tamu) {
            alert()->success('Success', 'Berhasil mengkonfirmasi Peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi Peminjaman!');
        }

        return redirect('admin/tagihan');
    }
}
