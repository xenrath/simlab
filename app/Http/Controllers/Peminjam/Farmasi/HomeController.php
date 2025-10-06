<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        return view('peminjam.farmasi.index');
    }

    public function barang_cari(Request $request)
    {
        $nama     = $request->barang_nama;
        $ruang_id = $request->barang_ruang_id;
        $limit    = (int) $request->input('barang_page', 10);

        $query = Barang::select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama');

        if ($ruang_id) {
            $query->where('ruang_id', $ruang_id);
        } else {
            $query->whereHas('ruang', function ($q) {
                $q->where('tempat_id', 2);
            });
        }

        if ($nama) {
            $query->where('nama', 'like', "%{$nama}%");
        }

        return $query->take($limit)->get();
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

    public function estafet_tambah($id)
    {
        $barangs = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruang_id',
                'detail_pinjams.jumlah'
            )
            ->with('ruang:id,nama')
            ->get();

        return $barangs;
    }

    public function bahan_cari(Request $request)
    {
        $keyword = $request->input('keyword');
        $limit = (int) $request->input('page', 10); // default ke 10 jika tidak dikirim

        $bahans = Bahan::where('prodi_id', 4)
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
