<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\PeminjamanTamu;
use App\Models\Pinjam;
use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class PeminjamanController extends Controller
{
    public function index()
    {
        $peminjaman_tamus = PeminjamanTamu::where('peminjaman_tamus.status', '!=', 'tagihan')
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

        return view('admin.peminjaman.index', compact('peminjaman_tamus'));
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

        return view('admin.peminjaman.show', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
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

        return view('admin.peminjaman.konfirmasi', compact('peminjaman_tamu', 'detail_peminjaman_tamus'));
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

        return redirect('admin/peminjaman');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->forceDelete();
        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }
        if ($detailpinjams) {
            foreach ($detailpinjams as $detailpinjam) {
                $barang = Barang::where('id', $detailpinjam->barang_id)->first();

                $barang->update([
                    'normal' => $barang->normal + $detailpinjam->jumlah
                ]);

                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }

    public function get_items(Request $request)
    {
        $items = $request->items;

        if ($items) {
            $barangs = Barang::whereIn('id', $items)->orderBy('nama')->select('id', 'nama')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->where('nama', 'like', "%$keyword%")->select('id', 'nama')->get();
        
        return $barangs;
    }

    public function add_item($id)
    {
        $barang = Barang::where('id', $id)->select('id', 'nama')->first();

        return $barang;
    }

    public function hubungi($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $pinjam->peminjam->telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $pinjam->peminjam->telp);
        }
    }

    public function telp($value)
    {
        if (substr($value, 0, 2) == '62') {
            $telp = substr($value, 2);
        } elseif (substr($value, 0, 1) == '0') {
            $telp = substr($value, 1);
        } elseif (substr($value, 0, 1) != '8') {
            $telp = $value;
        }

        return $telp;
    }
}
