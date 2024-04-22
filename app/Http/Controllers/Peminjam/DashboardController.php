<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isLabTerpadu()) {
            return redirect('peminjam/labterpadu');
        } elseif (auth()->user()->isFarmasi()) {
            return redirect('peminjam/farmasi');
        }
    }

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('nama', 'like', "%$keyword%")
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return $barangs;
    }

    public function add_item($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->first();

        return $barang;
    }

    public function delete_item($id)
    {
        if (DetailPinjam::where('id', $id)->exists()) {
            $detail_pinjam = DetailPinjam::findOrFail($id);
            $detail_pinjam->delete();
        }

        return true;
    }

    public function search_anggotas(Request $request)
    {
        $keyword = $request->keyword;
        $subprodi_id = auth()->user()->subprodi_id;
        $users = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])->where(function ($query) use ($keyword) {
            $query->where('nama', 'like', "%$keyword%");
            $query->orWhere('kode', 'like', "%$keyword%");
        })
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();

        return $users;
    }

    public function add_anggota($id)
    {
        $user = User::where('id', $id)->select('id', 'kode', 'nama')->first();
        return $user;
    }

    public function get_estafet($id)
    {
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total'
            )
            ->get();

        return $detail_pinjams;
    }
}
