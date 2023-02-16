<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id;

        $menunggus = Pinjam::whereHas('ruang', function ($query) use ($id) {
            $query->where('laboran_id', $id);
        })->where('status', 'menunggu')->orderBy('id', 'DESC')->get();
        $disetujuis = Pinjam::whereHas('ruang', function ($query) use ($id) {
            $query->where('laboran_id', $id);
        })->where('status', 'disetujui')->orderBy('id', 'DESC')->get();
        $selesais = Pinjam::whereHas('ruang', function ($query) use ($id) {
            $query->where('laboran_id', $id);
        })
            ->where('status', 'ditolak')
            ->orWhere('status', 'selesai')
            ->orderBy('id', 'DESC')->get();

        return view('laboran.index', compact('menunggus', 'disetujuis', 'selesais'));
    }
}
