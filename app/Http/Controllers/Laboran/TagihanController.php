<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class TagihanController extends Controller
{
    public function index()
    {
        if (auth()->user()->ruangs->first()->tempat_id == '1') {
            return $this->index_lab_terpadu();
        } elseif (auth()->user()->ruangs->first()->tempat_id == '2') {
            return $this->index_farmasi();
        }
    }

    public function index_lab_terpadu()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'tagihan']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->get();

        return view('laboran.tagihan.index_lab_terpadu', compact('pinjams'));
    }

    public function index_farmasi()
    {
        $pinjams = Pinjam::where('status', 'tagihan')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->select(
                'id',
                'praktik_id',
                'peminjam_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'kategori',
            )
            ->with('praktik:id,nama', 'peminjam:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->get();
        // 
        return view('laboran.tagihan.index_farmasi', compact('pinjams'));
    }

    public function show($id)
    {
        if (auth()->user()->ruangs->first()->tempat_id == '1') {
            return $this->show_lab_terpadu($id);
        } elseif (auth()->user()->ruangs->first()->tempat_id == '2') {
            return $this->show_farmasi($id);
        }
    }

    public function show_lab_terpadu($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->show_lab($id);
        } else if ($praktik_id == 2) {
            return $this->show_kelas($id);
        } else if ($praktik_id == 3) {
            return $this->show_luar($id);
        }
    }

    public function show_lab($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'bahan'
            )
            ->with('praktik:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
            })
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where([
            ['pinjam_id', $id],
            ['status', false]
        ])
            ->select(
                'id',
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');

        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('laboran.tagihan.show_lab', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->select(
                'id',
                'praktik_id',
                'laboran_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'keterangan',
                'bahan'
            )
            ->with('praktik:id,nama', 'laboran:id,nama')
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where([
            ['pinjam_id', $id],
            ['status', false]
        ])
            ->select(
                'id',
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');

        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('laboran.tagihan.show_kelas', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'keterangan',
                'bahan'
            )
            ->with('praktik:id,nama', 'laboran:id,nama')
            ->first();
        $detail_pinjams = DetailPinjam::where([
            ['pinjam_id', $id],
            ['status', false]
        ])
            ->select(
                'id',
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');

        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('laboran.tagihan.show_luar', compact(
            'pinjam',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_farmasi($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('kategori', 'status')->first();
        // 
        if ($pinjam->status != 'tagihan') {
            alert()->error('Error', 'Gagal menemukan Tagihan Peminjaman!');
            return back();
        }
        // 
        if ($pinjam->kategori == 'normal') {
            return $this->show_farmasi_mandiri($id);
        } elseif ($pinjam->kategori == 'estafet') {
            return $this->show_farmasi_estafet($id);
        }
    }

    public function show_farmasi_mandiri($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id]
        ])
            ->select(
                'id',
                'peminjam_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
            )
            ->with('peminjam:id,nama', 'ruang:id,nama')
            ->first();
        $detail_pinjams = DetailPinjam::where([
            ['pinjam_id', $id],
            ['status', false]
        ])
            ->select(
                'id',
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        // 
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');

        $tagihan_detail = array();
        // 
        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }
        // 
        return view('laboran.tagihan.show_farmasi_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function show_farmasi_estafet($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id]
        ])
            ->select(
                'id',
                'peminjam_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
            )
            ->with('peminjam:id,nama', 'ruang:id,nama')
            ->first();
        // 
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        // 
        $detail_pinjams = DetailPinjam::where([
            ['pinjam_id', $id],
            ['status', false]
        ])
            ->select(
                'id',
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
                'pelakus'
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        // 
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();
        // 
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');
        // 
        $tagihan_detail = array();
        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;
            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }
            $tagihan_detail[$key] = $jumlah;
        }
        // 
        return view('laboran.tagihan.show_farmasi_estafet', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    public function update(Request $request, $id)
    {
        if (!array_sum($request->jumlah)) {
            alert()->error('Error', 'Isikan form pengembalian dengan benar!');
            return back();
        }
        // 
        $detail_pinjams = DetailPinjam::where([
            ['pinjam_id', $id],
            ['status', false]
        ])
            ->select(
                'id',
                'barang_id',
                'jumlah',
                'rusak',
                'hilang',
            )
            ->get();
        // 
        $tagihan = 0;
        foreach ($detail_pinjams as $detail_pinjam) {
            $jumlah = $request->jumlah[$detail_pinjam->id];
            if ($jumlah) {
                $normal = Barang::where('id', $detail_pinjam->barang_id)->value('normal');
                $tagihan_jumlah = 0;
                $tagihan_peminjamans = TagihanPeminjaman::where([
                    ['pinjam_id', $id],
                    ['detail_pinjam_id', $detail_pinjam->id]
                ])->select('jumlah')->get();
                if (count($tagihan_peminjamans)) {
                    foreach ($tagihan_peminjamans as $tagihan_peminjaman) {
                        $tagihan_jumlah += $tagihan_peminjaman->jumlah;
                    }
                }
                // 
                TagihanPeminjaman::create([
                    'pinjam_id' => $id,
                    'detail_pinjam_id' => $detail_pinjam->id,
                    'jumlah' => $jumlah
                ]);
                // 
                $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang - $tagihan_jumlah;
                if ($jumlah != $rusak_hilang) {
                    $tagihan += 1;
                    $detail_pinjam_status = false;
                } else {
                    $detail_pinjam_status = true;
                }
                // 
                DetailPinjam::where('id', $detail_pinjam->id)->update([
                    'status' => $detail_pinjam_status
                ]);
                // 
                Barang::where('id', $detail_pinjam->barang_id)->update([
                    'normal' => $normal + $rusak_hilang,
                ]);
            } else {
                $tagihan += 1;
            }
        }
        // 
        if ($tagihan) {
            $peminjaman_tamu_status = 'tagihan';
        } else {
            $peminjaman_tamu_status = 'selesai';
        }
        // 
        $peminjaman_tamu = Pinjam::where('id', $id)->update([
            'status' => $peminjaman_tamu_status
        ]);
        // 
        if ($peminjaman_tamu) {
            alert()->success('Success', 'Berhasil mengkonfirmasi Tagihan');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi Tagihan!');
        }
        // 
        return redirect('laboran/tagihan');
    }

    public function konfirmasiold(Request $request, $id)
    {
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $rusak = 0;
        $hilang = 0;

        foreach ($detailpinjams as $detailpinjam) {
            if ($detailpinjam->rusak > 0) {
                $rusak = $request->input('rusak-' . $detailpinjam->id);

                $jumlah = $detailpinjam->rusak - $rusak;

                // return $jumlah;

                DetailPinjam::where('id', $detailpinjam->id)->update([
                    'rusak' => $jumlah
                ]);
            }
            if ($detailpinjam->hilang > 0) {
                $hilang = $request->input('hilang-' . $detailpinjam->id);

                $jumlah = $detailpinjam->hilang - $hilang;

                // return $jumlah;

                DetailPinjam::where('id', $detailpinjam->id)->update([
                    'hilang' => $jumlah
                ]);
            }

            $normal = $detailpinjam->barang->normal + $rusak + $hilang;
            $rusak = $detailpinjam->barang->rusak + $rusak;
            $total = $normal + $rusak;

            Barang::where('id', $detailpinjam->barang_id)->update([
                'normal' => $normal,
                'rusak' => $rusak,
                'total' => $total
            ]);
        }

        alert()->success('Berhasil', 'Barang berhasil dikembalikan');

        return redirect('laboran/tagihan');
    }

    public function hubungi($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $pinjam->peminjam->telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $pinjam->peminjam->telp);
        }
    }
}
