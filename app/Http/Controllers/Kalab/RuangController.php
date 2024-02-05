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
                'laboran_id'
            )
            ->with('laboran:id,nama')
            ->paginate(10);

        return view('kalab.ruang.index', compact('ruangs'));
    }

    public function show($id)
    {
        $ruang = Ruang::where('id', $id)->first();

        return view('kalab.ruang.show', compact('ruang'));
    }
}
