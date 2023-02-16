<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pengambilan;
use App\Models\Ruang;
use Illuminate\Http\Request;

class PengambilanController extends Controller
{
    public function index()
    {
        $pengambilans = Pengambilan::paginate(10);

        return view('admin.pengambilan.index', compact('pengambilans'));
    }

    public function create()
    {
        $ruangs = Ruang::where('prodi', 'farmasi')->get();
        $bahans = Barang::where('kategori', 'bahan')->get();

        return view('admin.pengambilan.create', compact('ruangs', 'bahans'));
    }

    public function store(Request $request)
    {
    }

    public function show($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function ruang($id)
    {
        $ruang = Ruang::where('id', $id)->with('laboran')->first();
        return json_encode($ruang);
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
}
