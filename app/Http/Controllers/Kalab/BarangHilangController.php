<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;

class BarangHilangController extends Controller
{
    public function index()
    {
        $hilangs = DetailPinjam::where('hilang', '>', '0')->paginate(10);

        return view('kalab.baranghilang.index', compact('hilangs'));
    }

    public function show($id)
    {
        $hilangs = DetailPinjam::where([
            ['barang_id', $id],
            ['hilang', '>', '0']
        ])->get();

        return view('kalab.hilang-detail', compact('hilangs'));
    }
}
