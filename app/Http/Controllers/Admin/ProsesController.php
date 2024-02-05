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
            ->with('tamu:id,nama,institusi')
            ->get();

        return view('admin.proses.index', compact('peminjaman_tamus'));
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
                'id',
                'barang_id',
                'total',
            )
            ->with('barang:id,nama')
            ->get();

        return view('admin.proses.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
    }

    public function update(Request $request, $id)
    {
        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->select('id', 'total', 'barang_id')
            ->get();

        $errors = array();
        $datas = array();

        foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu) {
            $barang = Barang::where('id', $detail_peminjaman_tamu->barang_id)->select('nama')->first();

            $rusak = $request->input('rusak-' . $detail_peminjaman_tamu->id);
            $hilang = $request->input('hilang-' . $detail_peminjaman_tamu->id);

            $total = $rusak + $hilang;

            $datas[$detail_peminjaman_tamu->id] = array('rusak' => $rusak, 'hilang' => $hilang);

            if ($total > $detail_peminjaman_tamu->total) {
                array_push($errors, '<strong>' . $barang->nama . '</strong>, jumlah penambahan barang rusak dan hilang melebihi jumlah barang yang dipinjam!');
            }
        }

        if (count($errors) > 0) {
            return back()->with('errors', $errors)->with('datas', $datas);
        }

        $tagihan = 0;

        foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu) {
            $barang = Barang::where('id', $detail_peminjaman_tamu->barang_id)->select('normal', 'rusak', 'hilang')->first();

            $rusak = $request->input('rusak-' . $detail_peminjaman_tamu->id);
            $hilang = $request->input('hilang-' . $detail_peminjaman_tamu->id);

            $rusak_hilang = $rusak + $hilang;
            $normal = $detail_peminjaman_tamu->total - $rusak_hilang;

            if ($rusak_hilang != 0) {
                $tagihan += 1;
                $detail_peminjaman_tamu_status = false;
            } else {
                $detail_peminjaman_tamu_status = true;
            }

            $barang_normal = $barang->normal - $rusak_hilang;
            $barang_rusak = $barang->rusak + $rusak;
            $barang_hilang = $barang->hilang + $hilang;

            Barang::where('id', $detail_peminjaman_tamu->barang_id)->update([
                'normal' => $barang_normal,
                'rusak' => $barang_rusak,
                'hilang' => $barang_hilang,
            ]);

            DetailPeminjamanTamu::where('id', $detail_peminjaman_tamu->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak,
                    'hilang' => $hilang,
                    'status' => $detail_peminjaman_tamu_status
                ]);
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

        return redirect('admin/proses');
    }

    public function destroy($id)
    {
        $peminjaman_tamu = PeminjamanTamu::where('id', $id)->first();

        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)->get();

        foreach ($detail_peminjaman_tamus as $detail_peminjaman_tamu) {
            $detail_peminjaman_tamu->delete();
        }

        $peminjaman_tamu->delete();

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return back();
    }
}
