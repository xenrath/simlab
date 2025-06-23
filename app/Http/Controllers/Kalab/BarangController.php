<?php

namespace App\Http\Controllers\Kalab;

use App\Exports\BarangRusakExport;
use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Prodi;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $prodi_id = $request->get('prodi_id');

        if ($prodi_id != "" && $keyword != "") {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->where('nama', 'LIKE', "%$keyword%")
                ->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->paginate(10);
        } elseif ($prodi_id != "" && $keyword == "") {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })
                ->with('ruang', function ($query) {
                    $query->select('id', 'nama', 'prodi_id');
                    $query->with('prodi:id,singkatan');
                })
                ->orderBy('nama')
                ->paginate(10);
        } elseif ($prodi_id == "" && $keyword != "") {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->where('nama', 'LIKE', "%$keyword%")
                ->with('ruang', function ($query) {
                    $query->select('id', 'nama', 'prodi_id');
                    $query->with('prodi:id,singkatan');
                })
                ->orderBy('nama')
                ->paginate(10);
        } else {
            $barangs = Barang::select(
                'id',
                'nama',
                'rusak',
                'hilang',
                'ruang_id'
            )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->paginate(10);
        }

        $prodis = Prodi::where('is_prodi', true)->select('id', 'singkatan')->get();

        return view('kalab.barang.index', compact('barangs', 'prodis'));
    }

    public function show($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'kode',
                'nama',
                'ruang_id',
                'total',
                'normal',
                'rusak',
                'keterangan',
                'gambar'
            )
            ->with('ruang:id,nama')
            ->first();

        return view('kalab.barang.show', compact('barang'));
    }

    public function rusak(Request $request)
    {
        $barangs = Barang::where('rusak', '>', '0')
            ->select(
                'id',
                'nama',
                'rusak',
                'hilang',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->paginate(10);
        // 
        return view('kalab.barang.rusak', compact('barangs'));
    }

    public function hilang()
    {
        $detail_pinjams = DetailPinjam::where('hilang', '>', '0')
            ->select(
                'barang_id',
                'hilang',
                'created_at'
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('kalab.barang.hilang', compact('detail_pinjams'));
    }

    public function unduh()
    {
        return Excel::download(new BarangRusakExport, 'barang-rusak.xlsx');
    }
}
