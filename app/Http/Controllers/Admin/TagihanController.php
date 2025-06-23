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
            ->orderByDesc('tanggal_awal')
            ->orderByDesc('created_at')
            ->get();
        // 
        return view('admin.tagihan.index', compact('peminjaman_tamus'));
    }

    public function show($id)
    {
        // Ambil data utama peminjaman
        $peminjaman_tamu = PeminjamanTamu::select(
            'id',
            'tamu_id',
            'lama',
            'keperluan',
            'tanggal_awal',
            'tanggal_akhir'
        )
            ->with('tamu:id,nama,telp,institusi,alamat')
            ->findOrFail($id);

        // Ambil detail peminjaman yang belum selesai (status = 0)
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->where('status', 0)
            ->select('id', 'barang_id', 'total', 'rusak', 'hilang')
            ->with([
                'barang:id,ruang_id,nama',
                'barang.ruang:id,nama'
            ])
            ->get();

        // Ambil tagihan dengan relasi sekaligus barang & ruang
        $tagihan_peminjaman_tamus = TagihanPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select('id', 'detail_peminjaman_tamu_id', 'jumlah', 'created_at')
            ->with([
                'detail_peminjaman_tamu:id,barang_id',
                'detail_peminjaman_tamu.barang:id,ruang_id,nama',
                'detail_peminjaman_tamu.barang.ruang:id,nama'
            ])
            ->get();

        // Grouping tagihan by detail_id dan hitung jumlahnya
        $tagihan_detail = $tagihan_peminjaman_tamus
            ->groupBy('detail_peminjaman_tamu_id')
            ->map(function ($items) {
                return $items->sum('jumlah');
            })
            ->toArray();

        // Return ke view
        return view('admin.tagihan.show', compact(
            'peminjaman_tamu',
            'detail_peminjaman_tamus',
            'tagihan_peminjaman_tamus',
            'tagihan_detail'
        ));
    }

    public function update(Request $request, $id)
    {
        if (!array_sum($request->jumlah ?? [])) {
            alert()->error('Error', 'Isikan form pengembalian dengan benar!');
            return back();
        }

        $detail_peminjaman_tamus = DetailPeminjamanTamu::where([
            ['peminjaman_tamu_id', $id],
            ['status', 0]
        ])
            ->select('id', 'barang_id', 'total', 'rusak', 'hilang')
            ->with('barang:id,normal')
            ->get()
            ->keyBy('id'); // untuk akses cepat per ID

        // Group tagihan sebelumnya
        $tagihan_group = TagihanPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select('detail_peminjaman_tamu_id', 'jumlah')
            ->get()
            ->groupBy('detail_peminjaman_tamu_id');

        $tagihan = 0;

        foreach (($request->jumlah ?? []) as $detailId => $jumlah) {
            $detail = $detail_peminjaman_tamus->get($detailId);

            if (!$detail || !$jumlah) {
                $tagihan++;
                continue;
            }

            $normal = $detail->barang->normal;
            $tagihanSebelumnya = $tagihan_group->get($detailId)?->sum('jumlah') ?? 0;
            $rusakHilang = $detail->rusak + $detail->hilang;

            // Simpan tagihan baru
            TagihanPeminjamanTamu::create([
                'peminjaman_tamu_id' => $id,
                'detail_peminjaman_tamu_id' => $detailId,
                'jumlah' => $jumlah
            ]);

            $detailStatus = ($jumlah == ($rusakHilang - $tagihanSebelumnya));

            // Update status detail
            $detail->update([
                'status' => $detailStatus
            ]);

            // Update stok barang
            $detail->barang->update([
                'normal' => $normal + ($rusakHilang - $tagihanSebelumnya)
            ]);

            if (!$detailStatus) {
                $tagihan++;
            }
        }

        // Update status peminjaman
        $status = $tagihan > 0 ? 'tagihan' : 'selesai';

        $updated = PeminjamanTamu::where('id', $id)->update(['status' => $status]);

        if ($updated) {
            alert()->success('Success', 'Berhasil mengkonfirmasi Peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi Peminjaman!');
        }

        return redirect('admin/tagihan');
    }
}
