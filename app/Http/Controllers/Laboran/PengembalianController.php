<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        if ($keyword != "") {
            $pinjams = Pinjam::where([
                ['kategori', 'normal'],
                ['status', 'disetujui']
            ])->whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderBy('tanggal_akhir', 'ASC')->whereHas('peminjam', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })->paginate(10);
        } else {
            $pinjams = Pinjam::where([
                ['kategori', 'normal'],
                ['status', 'disetujui']
            ])->whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderBy('id', 'DESC')->paginate(10);
        }

        return view('laboran.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
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
            $query->orderBy('nama', 'ASC');
        })->get();

        $barangs = Barang::where('normal', '>', '0')->orderBy('ruang_id', 'ASC')->get();

        return view('laboran.pengembalian.show', compact('pinjam', 'detailpinjams', 'barangs'));
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
        $pinjam = Pinjam::whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->where([
            ['id', $id],
            ['status', 'disetujui']
        ])->first();

        if (!$pinjam) {
            abort(404);
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'desc');
        })->get();

        return view('laboran.pengembalian.konfirmasi', compact('pinjam', 'detail_pinjams'));
    }

    public function p_konfirmasi(Request $request, $id)
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

        return redirect('laboran/pengembalian');
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
