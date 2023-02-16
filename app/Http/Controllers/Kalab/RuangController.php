<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Ruang;
use Illuminate\Http\Request;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $tempat_id = $request->get('tempat_id');

        if ($tempat_id != "") {
            $ruangs = Ruang::where('tempat_id', $tempat_id)->paginate(10);
        } else {
            $ruangs = Ruang::paginate(10);
        }

        return view('kalab.ruang.index', compact('ruangs'));
    }

    public function show($id)
    {
        $ruang = Ruang::where('id', $id)->first();

        return view('kalab.ruang.show', compact('ruang'));
    }
}
