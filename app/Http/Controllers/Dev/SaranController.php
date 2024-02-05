<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Saran;
use Illuminate\Http\Request;

class SaranController extends Controller
{
    public function index()
    {
        $sarans = Saran::select(
            'id',
            'kategori',
            'saran',
            'gambar'
        )->get();

        return view('dev.saran.index', compact('sarans'));
    }
}
