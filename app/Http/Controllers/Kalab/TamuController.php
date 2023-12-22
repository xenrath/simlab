<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;

class TamuController extends Controller
{
    public function index()
    {
        $tamus = Tamu::select('nama', 'institusi')->paginate(10);

        return view('kalab.tamu.index', compact('tamus'));
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        return view('kalab.tamu.show', compact('user'));
    }
}
