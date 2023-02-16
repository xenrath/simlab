<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\StokBahan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StokBahanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');
        $keyword = $request->get('keyword');
        $now = Carbon::now();

        if ($tanggal_awal != "" && $tanggal_akhir != "" && $keyword != "") {
            $stoks = StokBahan::whereHas('barang', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })->whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $tanggal_akhir)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } elseif ($tanggal_awal != "" && $tanggal_akhir != "") {
            $stoks = StokBahan::whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $tanggal_akhir)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } elseif ($tanggal_awal != "" && $keyword != "") {
            $stoks = StokBahan::whereHas('barang', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })->whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $now)
                ->orderBy('created_at', 'DESC')
                ->paginate(10);
        } elseif ($keyword != "") {
            $stoks = StokBahan::whereHas('barang', function ($query) use ($keyword) {
                $query->where('nama', 'LIKE', "%$keyword%");
            })->orderBy('created_at', 'DESC')->paginate(10);
        } else {
            $stoks = StokBahan::orderBy('created_at', 'DESC')->paginate(10);
        }

        return view('kalab.bahanmasuk.index', compact('stoks'));
    }

    public function show($id)
    {
        $stok = StokBahan::where('id', $id)->first();
        $bahan = Bahan::where('id', $stok->bahan_id)->first();
        $stoks = StokBahan::where('bahan_id', $bahan->id)->orderByDesc('created_at')->get();

        return view('kalab.bahanmasuk.show', compact('bahan', 'stoks'));
    }
}
