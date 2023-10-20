<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.peminjaman.index');
    }

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->where('nama', 'like', "%$keyword%")->select('id', 'nama')->get();
        return $barangs;
    }

    public function add_item($id)
    {
        $barang = Barang::where('id', $id)->select('id', 'nama')->first();

        return $barang;
    }
}
