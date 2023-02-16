<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\DetailPengambilan;
use App\Models\Pengambilan;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index()
    {
        $ruangs = Ruang::where('laboran_id', auth()->user()->id)->get();
        $ruang = $ruangs->first();
        $isadmin = Ruang::where([
            ['kode', '01'],
            ['laboran_id', auth()->user()->id]
        ])->orWhere([
            ['kode', '02'],
            ['laboran_id', auth()->user()->id]
        ])->first();
        if ($isadmin) {
            $pengambilans = Pengambilan::orderByDesc('created_at')->get();
        } else {
            $pengambilans = Pengambilan::whereHas('ruang', function ($query) {
                $query->where('laboran_id', auth()->user()->id);
            })->orderByDesc('created_at')->get();
        }

        return view('laboran.bahan.index', compact('pengambilans', 'isadmin'));
    }

    public function create()
    {
        $ruang = Ruang::where('laboran_id', auth()->user()->id)->first();
        if ($ruang->kode == '01') {
            $ruangs = Ruang::where('kode', '!=', '01')->get();
        } else {
            $ruangs = Ruang::where([
                ['tempat_id', $ruang->tempat_id],
                ['kode', '!=', '02']
            ])->get();
        }

        $bahans = Bahan::where('stok', '>', '0')->whereHas('ruang', function ($query) use ($ruang) {
            $query->where('tempat_id', $ruang->tempat_id);
        })->get();

        return view('laboran.bahan.create', compact('ruangs', 'bahans'));
    }

    public function ruang($id = null)
    {
        if ($id != null) {
            $ruang = Ruang::where('id', $id)->with('laboran', 'prodi', 'tempat')->first();
        } else {
            $ruang = null;
        }

        return json_encode($ruang);
    }

    public function pilih(Request $request)
    {
        $items = $request->items;

        if ($items) {
            $bahans = Bahan::whereIn('id', $items)->with('satuan')->orderBy('nama', 'ASC')->get();
        } else {
            $bahans = null;
        }

        return json_encode($bahans);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ruang_id' => 'required',
        ], [
            'ruang_id.required' => 'Ruangan harus dipilih!',
        ]);

        $bahan_id = $request->bahan_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if (!$bahan_id) {
            alert()->error('Error', 'Pilih barang terlebih dahulu!');
            return redirect()->back();
        }

        $bahans = Bahan::whereIn('id', $bahan_id)->get();

        for ($i = 0; $i < count($bahan_id); $i++) {
            $bahan = $bahans->where('id', $bahan_id[$i])->first();
            $sa = Satuan::where('id', $satuan[$i])->first();
            $kali = $bahan->satuan->kali / $sa->kali;
            $js = $jumlah[$i] * $kali;

            // return $js;

            if ($js > $bahan->stok) {
                alert()->error('Error!', 'Jumlah barang melebihi stok!');
                return back()->withInput();
            }
        }

        $pengambilan = Pengambilan::create(array_merge($request->all()));

        for ($i = 0; $i < count($bahan_id); $i++) {
            $bahan = $bahans->where('id', $bahan_id[$i])->first();
            $sa = Satuan::where('id', $satuan[$i])->first();
            $kali = $bahan->satuan->kali / $sa->kali;
            $js = $jumlah[$i] * $kali;

            DetailPengambilan::create(array_merge([
                'pengambilan_id' => $pengambilan->id,
                'bahan_id' => $bahan->id,
                'jumlah' => $jumlah[$i],
                'satuan_id' => $sa->id
            ]));

            $stok = $bahan->stok - $js;

            Bahan::where('id', $bahan->id)->update([
                'stok' => $stok
            ]);
        }

        alert()->success('Success', 'Berhasil menambahkan Peminjaman');

        return redirect('laboran/bahan');
    }

    public function show($id)
    {
        $isadmin = Ruang::where([
            ['laboran_id', auth()->user()->id],
            ['kode', '01']
        ])->orWhere([
            ['kode', '02'],
            ['laboran_id', auth()->user()->id]
        ])->first();
        $pengambilan = Pengambilan::where('id', $id)->first();
        $detailpengambilans = DetailPengambilan::where('pengambilan_id', $pengambilan->id)->get();

        return view('laboran.bahan.show', compact('pengambilan', 'detailpengambilans', 'isadmin'));
    }
}
