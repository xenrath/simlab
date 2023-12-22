<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Absen;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use stdClass;

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

    public function ruang()
    {
        $ruangs = Ruang::selectRaw('nama')->withCount('pinjams')->orderByDesc('pinjams_count')->limit(10)->get();

        $labels = $ruangs->pluck('nama');
        $data = $ruangs->pluck('pinjams_count');

        return view('kalab.grafik.ruang', compact('labels', 'data'));
    }

    public function barang(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $peminjam = $request->peminjam;

        if ($prodi_id != "" && $peminjam != "") {
            if ($peminjam == 'mahasiswa') {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', '!=', null);
                        });
                    });
                })->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })->with('detailpinjams.pinjam.peminjam')->get();
            } else {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', null);
                        });
                    });
                })->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })->with('detailpinjams.pinjam.peminjam')->get();
            }
        } else if ($prodi_id != "" && $peminjam == "") {
            $barangs = Barang::whereHas('ruang', function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })
                ->withCount('detailpinjams')
                ->get()
                ->where('detailpinjams_count', '>', '0');
        } else if ($prodi_id == "" && $peminjam != "") {
            if ($peminjam == 'mahasiswa') {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', '!=', null);
                        });
                    });
                })->with('detailpinjams.pinjam.peminjam')->get();
            } else {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', null);
                        });
                    });
                })->with('detailpinjams.pinjam.peminjam')->get();
            }
        } else {
            $barangs = Barang::withCount('detailpinjams')
                ->limit(100)
                ->get()
                ->where('detailpinjams_count', '>', '0');
        }

        // return response($barangs);

        $collection = collect();

        // $barangs = Barang::whereHas('detailpinjams', function ($query) {
        //     $query->whereHas('pinjam', function ($query) {
        //         $query->whereHas('peminjam', function ($query) {
        //             $query->where('kode', null);
        //         });
        //     });
        // })->with('detailpinjams.pinjam.peminjam')->get();

        if ($peminjam != "") {
            if ($peminjam == 'mahasiswa') {
                foreach ($barangs as $key => $barang) {
                    $jumlahdetail = array();
                    foreach ($barang->detailpinjams as $detailpinjam) {
                        if ($detailpinjam->pinjam != null) {
                            if ($detailpinjam->pinjam->peminjam->kode != null) {
                                $jumlahdetail[] += $detailpinjam->jumlah;
                            }
                        }
                    }

                    $collection->push(['nama' => $barang->nama, 'jumlah' => array_sum($jumlahdetail)]);
                }
            } else {
                foreach ($barangs as $key => $barang) {
                    $jumlahdetail = array();
                    foreach ($barang->detailpinjams as $detailpinjam) {
                        if ($detailpinjam->pinjam != null) {
                            if ($detailpinjam->pinjam->peminjam->kode == null) {
                                $jumlahdetail[] += $detailpinjam->jumlah;
                            }
                        }
                    }

                    $collection->push(['nama' => $barang->nama, 'jumlah' => array_sum($jumlahdetail)]);
                }
            }
        } else {
            foreach ($barangs as $key => $barang) {
                $jumlahdetail = array();
                foreach ($barang->detailpinjams as $detailpinjam) {
                    $jumlahdetail[] += $detailpinjam->jumlah;
                }

                $class = new stdClass();

                $class->nama = $barang->nama;
                $class->jumlah = array_sum($jumlahdetail);

                $collection->push(['nama' => $barang->nama, 'jumlah' => array_sum($jumlahdetail)]);
            }
        }

        // $barangs = Barang::with('detailpinjams.pinjam.peminjam')
        //     ->withCount('detailpinjams')
        //     ->orderByDesc('detailpinjams_count')
        //     ->get()
        //     ->where('detailpinjams_count', '>', '0');

        $barang = Barang::limit('100')->get();

        // return response($collection->sortByDesc('jumlah'));

        $labels = $collection->sortByDesc('jumlah')->pluck('nama');
        $data = $collection->sortByDesc('jumlah')->pluck('jumlah');

        $prodis = Prodi::where('id', '!=', '6')->get();

        return view('kalab.grafik.barang', compact('prodis', 'barangs', 'labels', 'data'));
    }

    public function barang1(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $peminjam = $request->peminjam;

        $tamus = User::where([
            ['role', 'peminjam'],
            ['kode', null]
        ])->pluck('kode');

        // return $tamus;

        if ($prodi_id != "") {
            $barangs = Barang::whereHas('ruang', function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })->selectRaw('nama')
                ->withCount('detailpinjams')
                ->orderByDesc('detailpinjams_count')
                ->get()
                ->where('detailpinjams_count', '>', '0');
        } else {
            $barangs = Barang::whereHas('detailpinjams', function ($query) {
                $query->whereHas('pinjam', function ($query) {
                    $query->whereHas('peminjam', function ($query) {
                        $query->where('kode', null);
                    });
                });
            })
                ->withCount('detailpinjams')
                ->get()
                ->where('detailpinjams_count', '>', '0');

            $jumlah = Barang::whereHas('detailpinjams', function ($query) {
                $query->whereHas('pinjam', function ($query) {
                    $query->whereHas('peminjam', function ($query) {
                        $query->where('kode', null);
                    });
                });
            })
                ->withCount('detailpinjams')
                ->get();
        }

        // $barangs = Barang::with('detailpinjams.pinjam.peminjam')
        //     ->withCount('detailpinjams')
        //     ->orderByDesc('detailpinjams_count')
        //     ->get()
        //     ->where('detailpinjams_count', '>', '0');

        return response($jumlah);

        $labels = $barangs->pluck('nama');
        $data = $barangs->pluck('detailpinjams_count');

        $prodis = Prodi::get();

        return view('kalab.grafik.barang', compact('prodis', 'barangs', 'labels', 'data'));
    }

    public function barang2(Request $request)
    {
        $prodi_id = $request->prodi_id;
        $peminjam = $request->peminjam;

        if ($prodi_id != "" && $peminjam != "") {
            if ($peminjam == 'mahasiswa') {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', '!=', null);
                        });
                    });
                })->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })->with('detailpinjams.pinjam.peminjam')->get();
            } else {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', null);
                        });
                    });
                })->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })->with('detailpinjams.pinjam.peminjam')->get();
            }
        } else if ($prodi_id != "" && $peminjam == "") {
            $barangs = Barang::whereHas('ruang', function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })
                ->withCount('detailpinjams')
                ->get()
                ->where('detailpinjams_count', '>', '0');
        } else if ($prodi_id == "" && $peminjam != "") {
            if ($peminjam == 'mahasiswa') {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', '!=', null);
                        });
                    });
                })->with('detailpinjams.pinjam.peminjam')->get();
            } else {
                $barangs = Barang::whereHas('detailpinjams', function ($query) {
                    $query->whereHas('pinjam', function ($query) {
                        $query->whereHas('peminjam', function ($query) {
                            $query->where('kode', null);
                        });
                    });
                })->with('detailpinjams.pinjam.peminjam')->get();
            }
        } else {
            $barangs = Barang::withCount('detailpinjams')
                ->limit(100)
                ->get()
                ->where('detailpinjams_count', '>', '0');
        }

        // return response($barangs);

        $nama = array();
        $jumlah = array();

        // $barangs = Barang::whereHas('detailpinjams', function ($query) {
        //     $query->whereHas('pinjam', function ($query) {
        //         $query->whereHas('peminjam', function ($query) {
        //             $query->where('kode', null);
        //         });
        //     });
        // })->with('detailpinjams.pinjam.peminjam')->get();

        if ($peminjam != "") {
            if ($peminjam == 'mahasiswa') {
                foreach ($barangs as $key => $barang) {
                    $jumlahdetail = array();
                    foreach ($barang->detailpinjams as $detailpinjam) {
                        if ($detailpinjam->pinjam != null) {
                            if ($detailpinjam->pinjam->peminjam->kode != null) {
                                $jumlahdetail[] += $detailpinjam->jumlah;
                            }
                        }
                    }

                    $nama[] = $barang->nama;
                    $jumlah[] = array_sum($jumlahdetail);
                }
            } else {
                foreach ($barangs as $key => $barang) {
                    $jumlahdetail = array();
                    foreach ($barang->detailpinjams as $detailpinjam) {
                        if ($detailpinjam->pinjam != null) {
                            if ($detailpinjam->pinjam->peminjam->kode == null) {
                                $jumlahdetail[] += $detailpinjam->jumlah;
                            }
                        }
                    }

                    $nama[] = $barang->nama;
                    $jumlah[] = array_sum($jumlahdetail);
                }
            }
        } else {
            foreach ($barangs as $key => $barang) {
                $jumlahdetail = array();
                foreach ($barang->detailpinjams as $detailpinjam) {
                    $jumlahdetail[] += $detailpinjam->jumlah;
                }

                $nama[] = $barang->nama;
                $jumlah[] = array_sum($jumlahdetail);
            }
        }

        // $barangs = Barang::with('detailpinjams.pinjam.peminjam')
        //     ->withCount('detailpinjams')
        //     ->orderByDesc('detailpinjams_count')
        //     ->get()
        //     ->where('detailpinjams_count', '>', '0');

        $labels = $nama;
        $data = $jumlah;

        $prodis = Prodi::where('id', '!=', '6')->get();

        return view('kalab.grafik.barang', compact('prodis', 'barangs', 'labels', 'data'));
    }

    public function cmp($a, $b)
    {
        return strcmp($a->jumlah, $b->jumlah);
    }
}
