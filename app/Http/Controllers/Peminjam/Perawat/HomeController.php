<?php

namespace App\Http\Controllers\Peminjam\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        return view('peminjam.perawat.index');
    }

    public function barang_cari(Request $request)
    {
        $keyword = $request->input('keyword');
        $limit = (int) $request->input('page', 10); // default ke 10 jika tidak dikirim

        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', 1);
        })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%");
            })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take($limit)
            ->get();

        return $barangs;
    }

    public function barang_tambah($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'id',
                'nama',
                'ruang_id',
            )
            ->with('ruang:id,nama')
            ->first();

        return $barang;
    }

    public function bahan_cari(Request $request)
    {
        $keyword = $request->input('keyword');
        $limit = (int) $request->input('page', 10); // default ke 10 jika tidak dikirim

        $bahans = Bahan::whereHas('ruang', function ($query) {
            $query->where('tempat_id', 1);
        })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%");
            })
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->orderBy('nama')
            ->take($limit)
            ->get();

        return $bahans;
    }

    public function bahan_tambah($id)
    {
        $bahan = Bahan::where('id', $id)
            ->select(
                'id',
                'nama',
                'prodi_id',
                'satuan_pinjam',
            )
            ->with('prodi:id,nama')
            ->first();

        return $bahan;
    }

    public function anggota_cari(Request $request)
    {
        $keyword = $request->input('keyword');
        $checkbox = $request->boolean('checkbox');
        $limit = (int) $request->input('page', 10);
        $current_user_id = auth()->id();
        $subprodi_id = auth()->user()->subprodi_id;
        $nim = Str::substr(auth()->user()->kode, 0, 5);

        $users = User::where('role', 'peminjam')
            ->where('subprodi_id', $subprodi_id)
            ->where('id', '!=', $current_user_id)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('kode', 'like', "%{$keyword}%");
                });
            })
            ->when($checkbox, function ($query) use ($nim) {
                $query->where('kode', 'like', $nim . '%');
            })
            ->select('id', 'kode', 'nama')
            ->orderBy('kode')
            ->take($limit)
            ->get();

        return $users;
    }

    public function anggota_tambah(Request $request)
    {
        $anggota_item = $request->anggota_item ?? array();

        if (count($anggota_item)) {
            $anggotas = User::where('role', 'peminjam')
                ->whereIn('id', $anggota_item)
                ->select('id', 'kode', 'nama')
                ->orderBy('kode')
                ->get();
            return $anggotas;
        } else {
            return array();
        }
    }
}
