<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use Carbon\Carbon;

class AbsenController extends Controller
{
    public function index()
    {
        $absens = Absen::orderByDesc('created_at')->paginate(10);
        $jumlah = Absen::whereDate('created_at', Carbon::today())->count();

        return view('kalab.absen.index', compact('absens', 'jumlah'));
    }
}
