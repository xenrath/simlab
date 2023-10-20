<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use App\Models\TagihanPeminjamanTamu;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class TagihanController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('peminjaman_tamus.status', 'tagihan')
            ->join('tamus', 'peminjaman_tamus.tamu_id', '=', 'tamus.id')->select(
                'peminjaman_tamus.id',
                'peminjaman_tamus.tanggal_awal',
                'peminjaman_tamus.tanggal_akhir',
                'peminjaman_tamus.keperluan',
                'tamus.nama as tamu_nama',
                'tamus.institusi as tamu_institusi'
            )
            ->get();

        return view('admin.peminjaman.tagihan.index', compact('peminjaman_tamus'));
    }

    public function show($id)
    {
        $peminjaman_tamu = PeminjamanTamu::where('peminjaman_tamus.id', $id)
            ->join('tamus', 'peminjaman_tamus.tamu_id', '=', 'tamus.id')
            ->select(
                'peminjaman_tamus.id',
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

        $detail_peminjaman_tamus = DetailPeminjamanTamu::where([
            ['detail_peminjaman_tamus.peminjaman_tamu_id', $id],
            ['detail_peminjaman_tamus.status', 0]
        ])
            ->join('barangs', 'detail_peminjaman_tamus.barang_id', '=', 'barangs.id')
            ->select(
                'detail_peminjaman_tamus.id',
                'detail_peminjaman_tamus.total',
                'detail_peminjaman_tamus.rusak',
                'detail_peminjaman_tamus.hilang',
                'barangs.nama'
            )
            ->get();

        $tagihan_peminjaman_tamus = TagihanPeminjamanTamu::where('tagihan_peminjaman_tamus.peminjaman_tamu_id', $id)
            ->join('detail_peminjaman_tamus', 'tagihan_peminjaman_tamus.detail_peminjaman_tamu_id', '=', 'detail_peminjaman_tamus.id')
            ->join('barangs', 'detail_peminjaman_tamus.barang_id', '=', 'barangs.id')
            ->select(
                'tagihan_peminjaman_tamus.id',
                'tagihan_peminjaman_tamus.jumlah',
                'tagihan_peminjaman_tamus.created_at',
                'barangs.nama'
            )
            ->get();

        $tagihan_group_by = TagihanPeminjamanTamu::where('tagihan_peminjaman_tamus.peminjaman_tamu_id', $id)
            ->join('detail_peminjaman_tamus', 'tagihan_peminjaman_tamus.detail_peminjaman_tamu_id', '=', 'detail_peminjaman_tamus.id')
            ->select(
                'tagihan_peminjaman_tamus.id',
                'tagihan_peminjaman_tamus.detail_peminjaman_tamu_id',
                'tagihan_peminjaman_tamus.jumlah'
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

        return view('admin.peminjaman.tagihan.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus', 'tagihan_peminjaman_tamus', 'tagihan_detail'));
    }

    public function konfirmasi(Request $request, $id)
    {
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where([
            ['detail_peminjaman_tamus.peminjaman_tamu_id', $id],
            ['detail_peminjaman_tamus.status', 0]
        ])
            ->join('barangs', 'detail_peminjaman_tamus.barang_id', '=', 'barangs.id')
            ->select(
                'detail_peminjaman_tamus.id',
                'detail_peminjaman_tamus.barang_id',
                'detail_peminjaman_tamus.total',
                'detail_peminjaman_tamus.rusak',
                'detail_peminjaman_tamus.hilang',
                'barangs.nama'
            )
            ->get();

        $tagihan = 0;

        foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu) {

            $jumlah = $request->jumlah[$detail_peminjaman_tamu->id];

            if ($jumlah > 0) {

                $barang = Barang::where('id', $detail_peminjaman_tamu->barang_id)->select('normal')->first();

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
                    'normal' => $barang->normal + $rusak_hilang,
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
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
        }

        return redirect('admin/peminjaman/tagihan');
    }

    public function hubungi($id)
    {
        $tamu = PeminjamanTamu::where('peminjaman_tamus.id', $id)
            ->join('tamus', 'peminjaman_tamus.tamu_id', '=', 'tamus.id')
            ->select('tamus.telp')
            ->first();

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $tamu->telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $tamu->telp);
        }
    }
}
