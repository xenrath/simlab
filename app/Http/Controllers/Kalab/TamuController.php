<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        // 
        $tamus = Tamu::query()
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%$keyword%");
                $query->orWhere('institusi', 'like', "%$keyword%");
            })
            ->select(
                'id',
                'nama',
                'institusi',
                'telp',
                'alamat'
            )
            ->paginate(10);
        // 
        return view('kalab.tamu.index', compact('tamus'));
    }

    public function show($id)
    {
        $tamu = Tamu::where('id', $id)->first();
        // 
        return view('kalab.tamu.show', compact('tamu'));
    }
}
