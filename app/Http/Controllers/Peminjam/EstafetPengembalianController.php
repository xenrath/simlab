<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class EstafetPengembalianController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'estafet'],
            ['status', 'disetujui'],
            ['peminjam_id', auth()->user()->id]
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where([
            ['kategori', 'estafet'],
            ['status', 'disetujui']
        ])->get();

        return view('peminjam.estafet.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        $prodi_id = auth()->user()->subprodi->prodi_id;
        $ruangs = Ruang::where('prodi_id', $prodi_id)->get();
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) use ($ruangs) {
            $query->where('tempat_id', $ruangs->first()->tempat_id);
        })->orderBy('ruang_id')->get();

        return view('peminjam.estafet.pengembalian.show', compact('pinjam', 'detailpinjams', 'kelompoks', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                if ($jumlah[$i] > $barang->normal) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return back();
                }
            }

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                DetailPinjam::create(array_merge([
                    'pinjam_id' => $id,
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah[$i],
                    'satuan_id' => '6'
                ]));

                $stok = $barang->normal - $jumlah[$i];

                Barang::where('id', $barang->id)->update([
                    'normal' => $stok
                ]);
            }
        }

        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);

        alert()->success('Success', 'Berhasil memperbarui Peminjaman');

        return redirect('peminjam/estafet/pengembalian');
    }
}
