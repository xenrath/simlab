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
use Illuminate\Support\Facades\Validator;

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
        $pinjam = Pinjam::where('id', $id)->first();

        $barang_id = $this->toArray(collect($request->barang_id));
        $jumlah = $this->toArray(collect($request->jumlah));

        $arr_jumlah = array();
        $item = json_encode(array());
        $item_id = array();

        $error_barang = array();

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            foreach ($barang_id as $i) {
                $barang = Barang::where('id', $i)->first();

                $validator = Validator::make($request->all(), [
                    'jumlah.' . $i => 'required',
                ]);

                if ($validator->fails()) {
                    array_push($error_barang, 'Jumlah barang ' . $barang->nama . ' belum diisi!');
                }
            }

            $item = $this->pilih($barang_id);
            $item_id = $item->pluck('id');

            for ($i = 0; $i < count($item); $i++) {
                $arr_jumlah[] = array('barang_id' => $barang_id[$i], 'jumlah' => $jumlah[$i]);
            }
        }

        if (count($error_barang) > 0) {
            return back()->withInput()
                ->with('error_barang', $error_barang)
                ->with('item', json_decode($item))
                ->with('item_id', collect($item_id))
                ->with('jumlah', collect($arr_jumlah));
        }

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                if ($jumlah[$i] > $barang->normal) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return back();
                }
            }
        }

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                DetailPinjam::create([
                    'pinjam_id' => $pinjam->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah[$i],
                    'satuan_id' => '6'
                ]);

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

        return redirect('peminjam/normal/pengembalian-new/' . $pinjam->id);
    }

    public function pilih($items)
    {
        if ($items) {
            $barangs = collect();

            $barangs = Barang::whereIn('id', $items)->with('satuan', 'ruang')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        // return json_encode($barangs);
        return $barangs;
    }

    public function toArray($data)
    {
        $array = array();
        foreach ($data as $value) {
            array_push($array, $value);
        }

        return $array;
    }
}
