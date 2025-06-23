<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\DetailPinjam;
use Illuminate\Http\Request;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        // 
        $bahans = Bahan::when(!empty($keyword), function ($query) use ($keyword) {
            $query->where('nama', 'like', "%$keyword%");
        })
            ->select(
                'kode',
                'nama',
                'ruang_id',
                'stok',
                'satuan_id',
                'gambar'
            )
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'tempat_id');
                $query->with('tempat:id,nama');
            })
            ->with('satuan:id,singkatan')
            ->paginate(10);
        // 
        return view('kalab.bahan.index', compact('bahans'));
    }

    public function show($id)
    {
        $bahan = Bahan::where('id', $id)
            ->select(
                'kode',
                'nama',
                'ruang_id',
                'stok',
                'satuan_id',
                'gambar'
            )
            ->with('ruang:id,nama', 'satuan:id,singkatan')
            ->first();

        return view('kalab.bahan.show', compact('bahan'));
    }
}
