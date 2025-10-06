<?php

namespace App\Http\Controllers\Laboran\Bidan;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\TagihanPeminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class TagihanController extends Controller
{
    public function index()
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

        return view('laboran.bidan.tagihan.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select('praktik_id', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'tagihan') {
            return redirect('laboran/bidan')->with('error', 'Peminjaman tidak ditemukan!');
        }

        switch ($pinjam->praktik_id) {
            case 1:
                return $this->show_lab($id);
            case 2:
                return $this->show_kelas($id);
            case 3:
                return $this->show_luar($id);
            default:
                abort(404, 'Halaman tidak ditemukan');
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

        return view('laboran.bidan.tagihan.show_lab', compact(
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

        return view('laboran.bidan.tagihan.show_kelas', compact(
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

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
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

        return view('laboran.bidan.tagihan.show_luar', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
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

        $tagihan = 0;
        foreach ($detail_pinjams as $detail_pinjam) {
            $jumlah = $request->jumlah[$detail_pinjam->id];
            if ($jumlah) {
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

                TagihanPeminjaman::create([
                    'pinjam_id' => $id,
                    'detail_pinjam_id' => $detail_pinjam->id,
                    'jumlah' => $jumlah
                ]);

                $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang - $tagihan_jumlah;
                if ($jumlah != $rusak_hilang) {
                    $tagihan += 1;
                    $detail_pinjam_status = false;
                } else {
                    $detail_pinjam_status = true;
                }

                DetailPinjam::where('id', $detail_pinjam->id)->update([
                    'status' => $detail_pinjam_status
                ]);
            } else {
                $tagihan += 1;
            }
        }

        if ($tagihan) {
            $pinjam_status = 'tagihan';
        } else {
            $pinjam_status = 'selesai';
        }

        $pinjam = Pinjam::where('id', $id)->update([
            'status' => $pinjam_status
        ]);

        if (!$pinjam) {
            return back()->with('error', 'Gagal mengonfirmasi Tagihan!');
        }

        return redirect('laboran/bidan/tagihan')->with('success', 'Berhasil mengonfirmasi Tagihan');
    }
}
