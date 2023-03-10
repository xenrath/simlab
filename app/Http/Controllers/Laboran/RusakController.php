<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class RusakController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::whereHas('detail_pinjams', function ($query) {
            $query->where('rusak', '>', '0')->orWhere('hilang', '>', '0');
        })->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orWhereHas('detail_pinjams', function ($query) {
            $query->where('rusak', '>', '0')->orWhere('hilang', '>', '0');
        })->where('laboran_id', auth()->user()->id)->get();

        return view('laboran.rusak.index', compact('pinjams'));
    }

    public function show($id)
    {
        $rusak = Pinjam::whereHas('detail_pinjams', function ($query) {
            $query->where('rusak', '>', '0');
        })->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orWhereHas('detail_pinjams', function ($query) {
            $query->where('rusak', '>', '0');
        })->where('laboran_id', auth()->user()->id)->where('id', $id)->with('detail_pinjams')->first();

        $hilang = Pinjam::whereHas('detail_pinjams', function ($query) {
            $query->where('hilang', '>', '0');
        })->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orWhereHas('detail_pinjams', function ($query) {
            $query->where('hilang', '>', '0');
        })->where('laboran_id', auth()->user()->id)->where('id', $id)->with('detail_pinjams')->first();

        $pinjam = Pinjam::where('id', $id)->first();

        return view('laboran.rusak.show', compact('rusak', 'hilang', 'pinjam'));
    }

    public function konfirmasi(Request $request, $id)
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

                // $stok = $detailpinjam->barang->stok + $hilang;

                // Barang::where('id', $detailpinjam->barang_id)->update([
                //     'stok' => $stok
                // ]);
            }

            $normal = $detailpinjam->barang->normal + $rusak + $hilang;

            Barang::where('id', $detailpinjam->barang_id)->update([
                'normal' => $normal
            ]);
        }

        alert()->success('Berhasil', 'Barang berhasil dikembalikan');

        return redirect('laboran/rusak');
    }
}
