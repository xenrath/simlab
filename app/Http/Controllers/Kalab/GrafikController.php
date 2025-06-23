<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Tahun;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class GrafikController extends Controller
{
    public function pengunjung()
    {
        // $labels = array("September", "Oktober", "November", "Desember", "Januari", "Februari");
        // $data = array("102", "133", "196", "187", "31", "42");

        $period = CarbonPeriod::create(today()->subMonths(6), '1 month', today()->subMonth());

        $dates = array();
        $labels = array();

        foreach ($period as $date) {
            $dates[] = $date->format('Y-m');
            $labels[] = date('M Y', strtotime($date));
        }

        $data = array();
        foreach ($dates as $key => $date) {
            $absens = Absen::whereMonth('created_at', date('m', strtotime($date)))->whereYear('created_at', date('Y', strtotime($date)))->get();
            $data[] = count($absens);
        }

        return view('kalab.grafik.pengunjung', compact('labels', 'data'));
    }

    public function ruang(Request $request)
    {
        $prodis = Prodi::where('id', '!=', '6')->get();
        $tahuns = Tahun::get('nama');
        // 
        $prodi_id = $request->prodi_id;
        $tahun = $request->tahun;
        $page = $request->page ?? 10;
        // 
        $ruangs = Ruang::select('id', 'nama')
            ->when(!empty($prodi_id), function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })
            ->withCount(['pinjams' => function ($query) use ($tahun) {
                $query->when(!empty($tahun), function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                });
            }])
            ->orderByDesc('pinjams_count')
            ->get()
            ->take($page);
        // 
        $collection = collect();
        // 
        foreach ($ruangs as $key => $ruang) {
            $collection->push([
                'nama' => $ruang->nama,
                'jumlah' => $ruang->pinjams_count
            ]);
        }
        // 
        $labels = $collection->pluck('nama');
        $data = $collection->pluck('jumlah');
        // 
        return view('kalab.grafik.ruang', compact('prodis', 'tahuns', 'ruangs', 'labels', 'data'));
    }

    public function barang(Request $request)
    {
        $prodis = Prodi::where('id', '!=', '6')->get();
        $tahuns = Tahun::get('nama');
        // 
        $prodi_id = $request->prodi_id;
        $peminjam = $request->peminjam;
        $kategori = !empty($request->peminjam) ? ($request->peminjam == 'mahasiswa' ? '!=' : '==') : '';
        $tahun = $request->tahun;
        $page = $request->page ?? 10;
        // 
        $barangs = Barang::select('id', 'nama')
            ->whereHas('detailpinjams', function ($query) use ($tahun, $peminjam, $kategori) {
                $query->when(!empty($tahun), function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                });
                $query->whereHas('pinjam', function ($query) use ($peminjam, $kategori) {
                    $query->when(!empty($peminjam), function ($query) use ($kategori) {
                        $query->whereHas('peminjam', function ($query) use ($kategori) {
                            $query->where('kode', $kategori, null);
                        });
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
        // 
        $collection = collect();
        // 
        foreach ($barangs as $key => $barang) {
            $collection->push([
                'nama' => $barang->nama,
                'jumlah' => $barang->detailpinjams->sum('jumlah')
            ]);
        }
        // 
        $labels = $collection->pluck('nama');
        $data = $collection->pluck('jumlah');
        // 
        return view('kalab.grafik.barang', compact('prodis', 'tahuns', 'barangs', 'labels', 'data'));
    }

    public function print_ruang(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $prodi = Prodi::where('id', $prodi_id)->value('nama');
        $tahun = $request->tahun;
        $page = $request->page ?? 10;
        // 
        $ruangs = Ruang::select('id', 'nama')
            ->when(!empty($prodi_id), function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })
            ->withCount(['pinjams' => function ($query) use ($tahun) {
                $query->when(!empty($tahun), function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                });
            }])
            ->orderByDesc('pinjams_count')
            ->get()
            ->take($page);
        // 
        $collection = collect();
        // 
        foreach ($ruangs as $key => $ruang) {
            $collection->push([
                'nama' => $ruang->nama,
                'jumlah' => $ruang->pinjams_count
            ]);
        }
        // 
        $labels = $collection->pluck('nama');
        $data = $collection->pluck('jumlah');
        // 
        $pdf = Pdf::loadview('kalab.grafik.print_ruang', compact('labels', 'data', 'prodi'));
        return $pdf->stream('print-ruang.pdf');
    }

    public function print_barang(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $prodi = Prodi::where('id', $prodi_id)->value('nama');
        $peminjam = $request->peminjam;
        $kategori = !empty($request->peminjam) ? ($request->peminjam == 'mahasiswa' ? '!=' : '==') : '';
        $tahun = $request->tahun;
        $page = $request->page ?? 10;
        // 
        $barangs = Barang::select('id', 'nama')
            ->whereHas('detailpinjams', function ($query) use ($tahun, $peminjam, $kategori) {
                $query->when(!empty($tahun), function ($q) use ($tahun) {
                    $q->whereYear('created_at', $tahun);
                });
                $query->whereHas('pinjam', function ($query) use ($peminjam, $kategori) {
                    $query->when(!empty($peminjam), function ($query) use ($kategori) {
                        $query->whereHas('peminjam', function ($query) use ($kategori) {
                            $query->where('kode', $kategori, null);
                        });
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
        // 
        $collection = collect();
        // 
        foreach ($barangs as $key => $barang) {
            $collection->push([
                'nama' => $barang->nama,
                'jumlah' => $barang->detailpinjams->sum('jumlah')
            ]);
        }
        // 
        $labels = $collection->sortByDesc('jumlah')->pluck('nama');
        $data = $collection->sortByDesc('jumlah')->pluck('jumlah');
        // 
        $pdf = Pdf::loadview('kalab.grafik.print_barang', compact('labels', 'data', 'prodi'));
        return $pdf->stream('print-barang.pdf');
    }
}
