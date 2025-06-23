<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Ruang;
use App\Models\Tamu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class DashboardController extends Controller
{
    public function index()
    {
        return view('kalab.index');
    }

    public function hubungi_tamu($id)
    {
        $telp = Tamu::where('id', $id)->value('telp');

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $telp);
        }
    }

    public function hubungi_user($id)
    {
        $telp = User::where('id', $id)->value('telp');

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $telp);
        }
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

    public function grafik_barang(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $peminjam = $request->peminjam == 'mahasiswa' ? '!=' : '==';
        $tahun = $request->tahun;
        $page = $request->page;

        return $peminjam;

        $barangs = Barang::select('id', 'nama')
            ->whereHas('detailpinjams', function ($query) use ($tahun, $peminjam) {
                $query->when(!empty($tahun), function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                });
                $query->whereHas('pinjam', function ($query) use ($peminjam) {
                    $query->whereHas('peminjam', function ($query) use ($peminjam) {
                        $query->where('kode', $peminjam, null);
                    });
                });
            })
            ->when(!empty($prodi_id), function ($query) use ($prodi_id) {
                $query->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                });
            })
            ->with('detailpinjams', function ($query) {
                $query->select('id', 'pinjam_id', 'barang_id', 'jumlah');
                $query->with('pinjam', function ($query) {
                    $query->select('id', 'peminjam_id');
                    $query->with('peminjam', function ($query) {
                        $query->select('id', 'kode');
                    });
                });
            })
            ->get()
            ->sortByDesc(function ($barang) {
                return $barang->detailpinjams->sum('jumlah');
            })
            ->values()
            ->take($page);

        $collection = collect();

        foreach ($barangs as $key => $barang) {
            $jumlahdetail = array();
            foreach ($barang->detailpinjams as $detailpinjam) {
                if ($detailpinjam->pinjam != null) {
                    if ($detailpinjam->pinjam->peminjam->kode == null) {
                        $jumlahdetail[] += $detailpinjam->jumlah;
                    }
                }
            }
            // 
            $collection->push(['nama' => $barang->nama, 'jumlah' => array_sum($jumlahdetail)]);
        }

        $labels = $collection->sortByDesc('jumlah')->pluck('nama');
        $data = $collection->sortByDesc('jumlah')->pluck('jumlah');

        $barangs = Barang::withCount('detailpinjams')
            ->having('detailpinjams_count', '>', 0)
            ->orderByDesc('detailpinjams_count')
            ->limit(10)
            ->get();

        return $barangs;
    }
}
