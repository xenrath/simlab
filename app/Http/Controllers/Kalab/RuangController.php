<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Ruang;

class RuangController extends Controller
{
    public function index()
    {
        $ruangs = Ruang::where([
            ['kode', '!=', '01'],
            ['kode', '!=', '02']
        ])
            ->select(
                'id',
                'nama',
                'tempat_id',
                'prodi_id',
                'laboran_id',
            )
            ->with('tempat:id,nama')
            ->with('prodi:id,singkatan')
            ->with('laboran:id,nama')
            ->paginate(10);
        // 
        return view('kalab.ruang.index', compact('ruangs'));
    }

    public function show($id)
    {
        $ruang = Ruang::where('id', $id)->first();

        return view('kalab.ruang.show', compact('ruang'));
    }
}
