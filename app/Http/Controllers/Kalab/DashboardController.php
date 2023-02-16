<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Ruang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'admin')->get();
        $laborans = User::where('role', 'laboran')->get();
        $peminjams = User::where('role', 'peminjam')->get();
        $ruangs = Ruang::get();

        $barangs = Barang::get();
        $bahans = Bahan::get();

        $barangrusaks = DetailPinjam::where('rusak', '>', '0')->get();
        $baranghilangs = DetailPinjam::where('hilang', '>', '0')->get();
        $bahanhabises = Bahan::where('stok', '0')->get();

        return view('kalab.index', compact(
            'admins',
            'laborans',
            'peminjams',
            'ruangs',
            'barangs',
            'bahans',
            'barangrusaks', 
            'baranghilangs', 
            'bahanhabises'
        ));
    }

    public function masuk(Request $request)
    {
        $tanggal_awal = $request->get('tanggal_awal');
        $tanggal_akhir = $request->get('tanggal_akhir');
        $keyword = $request->get('keyword');
        $now = Carbon::now();

        if ($tanggal_awal != "" && $tanggal_akhir != "" && $keyword != "") {
            $barangs = Barang::whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $tanggal_akhir)
                ->where('nama', 'LIKE', "%$keyword%")
                ->orderBy('nama', 'ASC')
                ->paginate(25);
        } elseif ($tanggal_awal != "" && $tanggal_akhir != "") {
            $barangs = Barang::whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $tanggal_akhir)
                ->orderBy('nama', 'ASC')
                ->paginate(25);
        } elseif ($tanggal_awal != "" && $keyword != "") {
            $barangs = Barang::whereDate('created_at', '>=', $tanggal_awal)
                ->whereDate('created_at', '<=', $now)
                ->where('nama', 'LIKE', "%$keyword%")
                ->orderBy('nama', 'ASC')
                ->paginate(25);
        } elseif ($keyword != "") {
            $barangs = Barang::where('nama', 'LIKE', "%$keyword%")
                ->orderBy('nama', 'ASC')
                ->paginate(25);
        } else {
            $barangs = Barang::orderBy('nama', 'ASC')->paginate(25);
        }

        return view('kalab.masuk', compact('barangs'));
    }

    public function masuk_detail($id)
    {
        $barang = Barang::where('id', $id)->first();

        return view('kalab.masuk-detail', compact('barang'));
    }

    public function rusak()
    {
        $rusaks = DetailPinjam::where('rusak', '>', '0')->paginate(10);

        return view('kalab.rusak', compact('rusaks'));
    }

    public function rusak_detail($id)
    {
        $rusaks = DetailPinjam::where([
            ['barang_id', $id],
            ['rusak', '>', '0']
        ])->get();

        return view('kalab.rusak-detail', compact('rusaks'));
    }

    public function hilang()
    {
        $hilangs = DetailPinjam::where('hilang', '>', '0')->paginate(10);

        return view('kalab.hilang', compact('hilangs'));
    }

    public function hilang_detail($id)
    {
        $hilangs = DetailPinjam::where([
            ['barang_id', $id],
            ['hilang', '>', '0']
        ])->get();

        return view('kalab.hilang-detail', compact('hilangs'));
    }

    public function habis()
    {
        $bahan_habises = Barang::where([
            ['kategori', 'bahan'],
            ['stok', '0']
        ])->paginate(10);

        return view('kalab.habis', compact('bahan_habises'));
    }

    public function habis_detail($id)
    {
        $habises = DetailPinjam::where('barang_id', $id)->get();

        return view('kalab.habis-detail', compact('habises'));
    }
}
