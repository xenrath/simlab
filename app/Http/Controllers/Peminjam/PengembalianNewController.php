<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
use Illuminate\Http\Request;

class PengembalianNewController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'disetujui'],
            ['peminjam_id', auth()->user()->id],
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where([
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])->get();

        return view('peminjam.pengembalian-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['peminjam_id', auth()->user()->id],
            ['status', 'disetujui'],
        ])->first();

        if (!$pinjam) {
            abort(404);
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $prodi = Prodi::where('nama', 'farmasi')->first();

        if (auth()->user()->prodi_id == $prodi->id) {
            $barangs = Barang::where([
                ['tempat_id', '2'],
                ['stok', '>', '0']
            ])->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = Barang::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '1');
            })->where('normal', '>', '0')->orderBy('ruang_id', 'ASC')->get();
        }

        return view('peminjam.pengembalian-new.show', compact('pinjam', 'detail_pinjams', 'barangs'));
    }

    public function update(Request $request, $id)
    {
        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if ($barang_id) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;

                if ($js > $barang->normal) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return back()->withInput();
                }
            }

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;

                DetailPinjam::create(array_merge([
                    'pinjam_id' => $id,
                    'barang_id' => $barang->id,
                    'jumlah' => $js,
                    'satuan_id' => $sa->id
                ]));

                $stok = $barang->normal - $js;

                Barang::where('id', $barang->id)->update([
                    'normal' => $stok
                ]);
            }
        }

        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);

        alert()->success('Success', 'Berhasil memperbarui peminjaman');

        return redirect('peminjam/normal/pengembalian-new/' . $id);
    }
}
