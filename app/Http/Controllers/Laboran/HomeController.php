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

        $menunggus = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        $disetujuis = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        $selesais = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui']
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui']
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })->get();

        return view('laboran.index', compact('menunggus', 'disetujuis', 'selesais'));
    }
}
