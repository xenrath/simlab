<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\PeminjamanTamu;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'tagihan']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'tagihan']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.praktik_id',
                'pinjams.ruang_id',
                'users.nama as user_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.keterangan',
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->get();

        return view('laboran.tagihan.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return redirect('laboran/tagihan/praktik-laboratorium/' . $id);
        } else if ($pinjam->praktik_id == 2) {
            return redirect('laboran/tagihan/praktik-kelas/' . $id);
        } else if ($pinjam->praktik_id == 3) {
            return redirect('laboran/tagihan/praktik-luar/' . $id);
        }

        // $pinjam = Pinjam::where('id', $id)->first();

        // $rusaks = DetailPinjam::where('pinjam_id', $pinjam->id)->where('rusak', '>', '0')->get();
        // $hilangs = DetailPinjam::where('pinjam_id', $pinjam->id)->where('hilang', '>', '0')->get();

        // return view('laboran.tagihan.show', compact('rusaks', 'hilangs', 'pinjam'));
    }

    public function konfirmasiold(Request $request, $id)
    {
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $rusak = 0;
        $hilang = 0;

        foreach ($detailpinjams as $detailpinjam) {
            if ($detailpinjam->rusak > 0) {
                $rusak = $request->input('rusak-' . $detailpinjam->id);

                $jumlah = $detailpinjam->rusak - $rusak;

                // return $jumlah;

                DetailPinjam::where('id', $detailpinjam->id)->update([
                    'rusak' => $jumlah
                ]);
            }
            if ($detailpinjam->hilang > 0) {
                $hilang = $request->input('hilang-' . $detailpinjam->id);

                $jumlah = $detailpinjam->hilang - $hilang;

                // return $jumlah;

                DetailPinjam::where('id', $detailpinjam->id)->update([
                    'hilang' => $jumlah
                ]);
            }

            $normal = $detailpinjam->barang->normal + $rusak + $hilang;
            $rusak = $detailpinjam->barang->rusak + $rusak;
            $total = $normal + $rusak;

            Barang::where('id', $detailpinjam->barang_id)->update([
                'normal' => $normal,
                'rusak' => $rusak,
                'total' => $total
            ]);
        }

        alert()->success('Berhasil', 'Barang berhasil dikembalikan');

        return redirect('laboran/tagihan');
    }

    public function konfirmasi(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where([
            ['detail_pinjams.pinjam_id', $id],
            ['detail_pinjams.status', 0]
        ])
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.barang_id',
                'detail_pinjams.jumlah',
                'detail_pinjams.rusak',
                'detail_pinjams.hilang',
                'barangs.nama'
            )
            ->get();

        $tagihan = 0;

        foreach ($detail_pinjams as $detail_pinjam) {
            $jumlah = $request->jumlah[$detail_pinjam->id];
            if ($jumlah > 0) {
                $barang = Barang::where('id', $detail_pinjam->barang_id)->select('normal')->first();
                $tagihan_jumlah = 0;
                $tagihan_peminjaman = TagihanPeminjaman::where([
                    ['pinjam_id', $id],
                    ['detail_pinjam_id', $detail_pinjam->id]
                ])->select('jumlah')->get();
                if (count($tagihan_peminjaman)) {
                    foreach ($tagihan_peminjaman as $t) {
                        $tagihan_jumlah += $t->jumlah;
                    }
                }
                TagihanPeminjaman::create([
                    'pinjam_id' => $id,
                    'detail_pinjam_id' => $detail_pinjam->id,
                    'jumlah' => $jumlah
                ]);
                $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang - $tagihan_jumlah;
                if ($jumlah != $rusak_hilang) {
                    $tagihan += 1;
                    $detail_pinjam_status = false;
                } else {
                    $detail_pinjam_status = true;
                }
                DetailPinjam::where('id', $detail_pinjam->id)->update([
                    'status' => $detail_pinjam_status
                ]);
                Barang::where('id', $detail_pinjam->barang_id)->update([
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

        $peminjaman_tamu = Pinjam::where('id', $id)->update([
            'status' => $peminjaman_tamu_status
        ]);

        if ($peminjaman_tamu) {
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
        }

        return redirect('laboran/tagihan');
    }
}
