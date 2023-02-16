<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function index()
    {
        $absens = Absen::paginate(10);
        $jumlah = Absen::whereDate('created_at', Carbon::today())->get();

        return view('kalab.absen.index', compact('absens', 'jumlah'));
    }
}
