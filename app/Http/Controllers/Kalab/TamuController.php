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

        if ($keyword != "") {
            $tamus = Tamu::where('nama', 'like', "%$keyword%")
                ->orWhere('institusi', 'like', "%$keyword%")
                ->select('id', 'nama', 'institusi')
                ->paginate(10);
        } else {
            $tamus = Tamu::select('id', 'nama', 'institusi')->paginate(10);
        }

        return view('kalab.tamu.index', compact('tamus'));
    }

    public function show($id)
    {
        $tamu = Tamu::where('id', $id)->first();

        return view('kalab.tamu.show', compact('tamu'));
    }
}
