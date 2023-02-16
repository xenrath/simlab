<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;

class BarangRusakController extends Controller
{
    public function index()
    {
        $rusaks = DetailPinjam::where('rusak', '>', '0')->paginate(10);

        return view('kalab.barangrusak.index', compact('rusaks'));
    }

    public function show($id)
    {
        $rusaks = DetailPinjam::where([
            ['barang_id', $id],
            ['rusak', '>', '0']
        ])->get();

        return view('kalab.barangrusak.show', compact('rusaks'));
    }
}
