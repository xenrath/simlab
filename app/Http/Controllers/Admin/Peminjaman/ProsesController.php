<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('peminjaman_tamus.status', 'proses')
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

        return view('admin.peminjaman.proses.index', compact('peminjaman_tamus'));
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

        return view('admin.peminjaman.proses.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
    }

    public function konfirmasi($id)
    {
        $peminjaman_tamu = PeminjamanTamu::where('peminjaman_tamus.id', $id)
            ->join('tamus', 'peminjaman_tamus.tamu_id', '=', 'tamus.id')
            ->select(
                'peminjaman_tamus.*',
                'tamus.nama as tamu_nama',
                'tamus.telp as tamu_telp',
                'tamus.institusi as tamu_institusi',
                'tamus.alamat as tamu_alamat'
            )
            ->first();

        $detail_peminjaman_tamus = DetailPeminjamanTamu::where('peminjaman_tamu_id', $id)
            ->join('barangs', 'detail_peminjaman_tamus.barang_id', '=', 'barangs.id')
            ->select(
                'detail_peminjaman_tamus.id',
                'detail_peminjaman_tamus.total',
                'barangs.nama'
            )
            ->get();

        return view('admin.peminjaman.proses.konfirmasi', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
    }

    public function konfirmasi_selesai(Request $request, $id)
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
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
        }

        return redirect('admin/peminjaman/proses');
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
