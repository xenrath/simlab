<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('status', 'proses')
            ->select(
                'id',
                'tamu_id',
                'tanggal_awal',
                'tanggal_akhir',
                'keperluan',
            )
            ->orderByDesc('tanggal_awal')
            ->orderByDesc('created_at')
            ->with('tamu:id,nama,institusi')
            ->get();
        // 
        return view('admin.proses.index', compact('peminjaman_tamus'));
    }

    public function show($id)
    {
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
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select('id', 'barang_id', 'total')
            ->with('barang:id,ruang_id,nama', 'barang.ruang:id,nama')
            ->orderBy('id')
            ->get();
        // 
        return view('admin.proses.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
    }

    public function update(Request $request, $id)
    {
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select('id', 'total', 'barang_id')
            ->get();

        $barang_ids = $detail_peminjaman_tamus->pluck('barang_id')->unique();
        $barangs = Barang::whereIn('id', $barang_ids)->get()->keyBy('id');

        $errors = [];
        $rusak = $request->rusak;
        $hilang = $request->hilang;

        // Validasi
        foreach ($detail_peminjaman_tamus as $detail) {
            $total = ($rusak[$detail->id] ?? 0) + ($hilang[$detail->id] ?? 0);
            if ($total > $detail->total) {
                $barang_nama = $barangs[$detail->barang_id]->nama ?? 'Barang tidak ditemukan';
                $errors[] = "<strong>{$barang_nama}</strong>, jumlah barang rusak dan hilang melebihi jumlah yang dipinjam!";
            }
        }

        if (!empty($errors)) {
            return back()->withInput()->with('errors', $errors);
        }

        $jumlah_rusak = 0;
        $jumlah_hilang = 0;

        foreach ($detail_peminjaman_tamus as $detail) {
            $rusak_val = $rusak[$detail->id] ?? 0;
            $hilang_val = $hilang[$detail->id] ?? 0;
            $total_kerusakan = $rusak_val + $hilang_val;
            $normal = $detail->total - $total_kerusakan;

            $barang = $barangs[$detail->barang_id];

            // Update stok barang
            $barang->update([
                'normal' => $barang->normal - $total_kerusakan,
                'rusak' => $barang->rusak + $rusak_val,
                'hilang' => $barang->hilang + $hilang_val,
            ]);

            // Update detail peminjaman
            $detail->update([
                'normal' => $normal,
                'rusak' => $rusak_val,
                'hilang' => $hilang_val,
                'status' => $total_kerusakan == 0, // true jika tidak ada kerusakan
            ]);

            $jumlah_rusak += $rusak_val;
            $jumlah_hilang += $hilang_val;
        }

        // Update status peminjaman utama
        $status = ($jumlah_rusak + $jumlah_hilang) ? 'tagihan' : 'selesai';

        $updated = PeminjamanTamu::where('id', $id)->update(['status' => $status]);

        if ($updated) {
            alert()->success('Success', 'Berhasil mengkonfirmasi Peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi Peminjaman!');
        }

        return redirect('admin/proses');
    }

    public function destroy($id)
    {
        $peminjaman_tamu = PeminjamanTamu::findOrFail($id);

        if ($peminjaman_tamu->status != 'proses') {
            alert()->error('Error', 'Gagal menghapus Peminjaman!');
            return back();
        }

        // Hapus semua detail dengan 1 query
        DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)->delete();

        // Hapus peminjaman utama
        $peminjaman_tamu->delete();

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return back();
    }
}
