<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class PengembalianNewController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->get();

        return view('laboran.pengembalian-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'ASC');
        })->get();

        $barangs = Barang::where('normal', '>', '0')->orderBy('ruang_id', 'ASC')->get();

        return view('laboran.pengembalian-new.show', compact('pinjam', 'detailpinjams', 'barangs'));
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->delete();

        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }

        if ($detailpinjams) {
            if ($pinjam->status != 'selesai') {
                foreach ($detailpinjams as $detailpinjam) {
                    $barang = Barang::where('id', $detailpinjam->barang_id)->first();
                    $barang->update([
                        'normal' => $barang->normal + $detailpinjam->jumlah
                    ]);
                }
            }
            foreach ($detailpinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('laboran/pengembalian-new');
    }

    public function pilih(Request $request)
    {
        $items = $request->items;
        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan')->orderBy('kategori', 'DESC')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }

    public function update(Request $request, $id)
    {
        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);

        if ($barang_id) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;
                if ($js > $barang->stok) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return redirect()->back()->withInput();
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
            }
        }

        alert()->success('Success', 'Berhasil mengajukan peminjaman');

        return back();
    }

    public function konfirmasi($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'desc');
        })->get();

        return view('laboran.pengembalian-new.konfirmasi', compact('pinjam', 'detail_pinjams'));
    }

    public function p_konfirmasi(Request $request, $id)
    {
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

        return redirect('laboran/pengembalian-new');
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
}
