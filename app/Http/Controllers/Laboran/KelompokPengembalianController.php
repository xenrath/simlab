<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Satuan;
use Illuminate\Http\Request;

class KelompokPengembalianController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'estafet'],
            ['status', 'disetujui']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        return view('laboran.kelompok.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        return view('laboran.kelompok.pengembalian.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
    }

    public function edit($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) use ($pinjam) {
            $query->where('tempat_id', $pinjam->ruang->tempat_id);
        })->orderBy('ruang_id')->get();

        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('laboran.kelompok.pengembalian.edit', compact(
            'pinjam',
            'barangs',
            'kelompoks',
            'detailpinjams',
        ));
    }

    public function update(Request $request, $id)
    {

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if ($barang_id != null && $jumlah != null && $satuan != null) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;

                // return $js;

                if ($js > $barang->normal) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return redirect()->back();
                }
                //  else if ($tanggal_kembali > $tanggal_pinjam) {
                //     alert()->error('Error!', 'Maksimal peminjaman 5 Hari!');
                //     return redirect()->back();
                // }
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

        $id = Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);

        // $tanggal_kembali = Carbon::parse($request->tanggal_kembali);
        // $tanggal_pinjam = Carbon::parse($request->tanggal_pinjam)->addDays(5);

        alert()->success('Success', 'Berhasil memperbarui peminjaman');

        return redirect('laboran/kelompok/pengembalian');
    }

    public function konfirmasi_pengembalian(Request $request, $id)
    {
        $pinjam = Pinjam::whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->where([
            ['id', $id],
            ['status', 'disetujui']
        ])->first();

        if (!$pinjam) {
            abort(404);
        }

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'desc');
        })->get();

        foreach ($detailpinjams as $detailpinjam) {
            $normal = $request->input('normal-' . $detailpinjam->id);
            $rusak = $request->input('rusak-' . $detailpinjam->id);
            $hilang = $request->input('hilang-' . $detailpinjam->id);

            $jumlah = $normal + $rusak + $hilang;

            if ($jumlah > $detailpinjam->jumlah) {
                alert()->error('Error!', 'Jumlah barang normal, rusak dan hilang melebihi jumlah barang yang dipinjam!');
                return redirect()->back();
            } elseif ($jumlah != $detailpinjam->jumlah) {
                alert()->error('Error!', 'Jumlah barang normal, rusak dan hilang tidak sama dengan jumlah barang yang dipinjam!');
                return redirect()->back();
            }
        }

        foreach ($detailpinjams as $detailpinjam) {
            $normal = $request->input('normal-' . $detailpinjam->id);
            $rusak = $request->input('rusak-' . $detailpinjam->id);
            $hilang = $request->input('hilang-' . $detailpinjam->id);
            $kelompok_id = $request->input('kelompok_id-' . $detailpinjam->id);

            $barang = Barang::where('id', $detailpinjam->barang_id)->first();

            $barang->update([
                'normal' => $barang->normal + $normal,
                'rusak' => $barang->rusak + $rusak,
            ]);

            DetailPinjam::where('id', $detailpinjam->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak,
                    'hilang' => $hilang,
                    'kelompok_id' => $kelompok_id
                ]);
        }

        $update = Pinjam::where('id', $id)->update([
            'status' => 'selesai'
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman');
        }

        return redirect('laboran/kelompok/pengembalian');
    }
}
