<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Ruang;
use Illuminate\Http\Request;

class MandiriController extends Controller
{
    public function index()
    {
        $ruang = Ruang::where('id', request()->get('ruang_id'))->first();
        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();

        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', 2);
        })
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama'
            )
            ->orderBy('nama', 'ASC')
            ->get();

        return view('peminjam.farmasi.mandiri.index', compact('ruang', 'ruangs', 'barangs'));
    }
}
