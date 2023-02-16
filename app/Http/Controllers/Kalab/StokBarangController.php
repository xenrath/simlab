<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Stok;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StokBarangController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');
        $keyword = $request->get('keyword');
        $now = Carbon::now();

        if ($tanggal_awal != "" && $tanggal_akhir != "" && $keyword != "") {
            $stoks = StokBarang::whereHas('barang', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })
                ->whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $tanggal_akhir)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } elseif ($tanggal_awal != "" && $tanggal_akhir != "") {
            $stoks = StokBarang::whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $tanggal_akhir)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } elseif ($tanggal_awal != "" && $keyword != "") {
            $stoks = StokBarang::whereHas('barang', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })
                ->whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $now)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } elseif ($keyword != "") {
            $stoks = StokBarang::whereHas('barang', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            $stoks = StokBarang::orderByDesc('created_at')->paginate(10);
        }

        return view('kalab.barangmasuk.index', compact('stoks'));
    }

    public function show($id)
    {
        $stok = StokBarang::where('id', $id)->first();
        $barang = Barang::where('id', $stok->id)->first();
        $stoks = StokBarang::where('barang_id', $barang->id)->orderByDesc('created_at')->get();

        return view('kalab.barangmasuk.show', compact('barang', 'stoks'));
    }
}
