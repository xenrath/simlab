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
        $pinjams = Pinjam::whereHas('detail_pinjams', function ($detailpinjams) {
            $detailpinjams->where('rusak', '>', '0')->orWhere('hilang', '>', '0');
        })->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orderBy('id', 'DESC')->paginate(10);

        // $detailpinjams = DetailPinjam::whereHas('pinjam', function ($pinjam) {
        //     $pinjam->whereHas('ruang', function ($ruang) {
        //         $ruang->whereHas('laborans', function ($laborans) {
        //             $laborans->where('id', auth()->user()->id);
        //         });
        //     });
        // })
        //     ->where('rusak', '>', '0')
        //     ->orWhere('hilang', '>', '0')->paginate(10);

        return view('laboran.rusak.index', compact('pinjams'));
    }

    public function show($id)
    {
        $rusak = Pinjam::whereHas('detail_pinjams', function ($detailpinjams) {
            $detailpinjams->where('rusak', '>', '0');
        })->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->where('id', $id)->with('detail_pinjams')->first();

        $hilang = Pinjam::whereHas('detail_pinjams', function ($detailpinjams) {
            $detailpinjams->where('hilang', '>', '0');
        })->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->where('id', $id)->with('detail_pinjams')->first();

        // $pinjam = Pinjam::where('id');

        // return response($hilang);

        $pinjam = Pinjam::where('id', $id)->first();

        // $detailpinjam = DetailPinjam::whereHas('pinjam', function ($pinjam) {
        //     $pinjam->whereHas('ruang', function ($ruang) {
        //         $ruang->whereHas('laborans', function ($laborans) {
        //             $laborans->where('id', auth()->user()->id);
        //         });
        //     });
        // })->where('id', $id)->first();

        // return response($detailpinjam);

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

        // foreach ($detailpinjams as $detailpinjam) {
        //     if ($detailpinjam->hilang > 0) {
        //         $hilang = $request->input('hilang-' . $detailpinjam->id);

        //         $jumlah = $detailpinjam->hilang - $hilang;

        //         // return $jumlah;

        //         DetailPinjam::where('id', $detailpinjam->id)->update([
        //             'hilang' => $jumlah
        //         ]);

        //         $stok = $detailpinjam->barang->stok + $hilang;

        //         Barang::where('id', $detailpinjam->barang_id)->update([
        //             'stok' => $stok
        //         ]);
        //     }
        // }

        alert()->success('Berhasil', 'Barang berhasil dikembalikan');

        return redirect('laboran/rusak');
    }
}
