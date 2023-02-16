<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;

class BahanHabisController extends Controller
{
    public function index()
    {
        $bahan_habises = Bahan::where('stok', '0')->paginate(10);

        return view('kalab.bahanhabis.index', compact('bahan_habises'));
    }

    public function show($id)
    {
        $habises = DetailPinjam::where('barang_id', $id)->get();

        return view('kalab.habis-detail', compact('habises'));
    }
}
