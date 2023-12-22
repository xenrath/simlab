<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\PeminjamanTamu;
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
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.praktik_id',
                'pinjams.ruang_id',
                'users.nama as user_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.keterangan',
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->paginate(6);

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
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return redirect('laboran/tagihan/praktik-laboratorium/' . $id);
        } else if ($pinjam->praktik_id == 2) {
            return redirect('laboran/tagihan/praktik-kelas/' . $id);
        } else if ($pinjam->praktik_id == 3) {
            return redirect('laboran/tagihan/praktik-luar/' . $id);
        }
    }

    public function show_farmasi($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id]
        ])
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
            ->first();
        if ($pinjam->kategori == 'normal') {
            $data_kelompok = array();
        } elseif ($pinjam->kategori == 'estafet') {
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
        }
        $detail_pinjams = DetailPinjam::where([
            ['detail_pinjams.pinjam_id', $id],
            ['detail_pinjams.status', 0]
        ])
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.jumlah',
                'detail_pinjams.rusak',
                'detail_pinjams.hilang',
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama'
            )
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id', 'jumlah');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama');
                });
            })
            ->get();
        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.detail_pinjam_id',
                'tagihan_peminjamans.jumlah'
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

        return view('laboran.tagihan.show_farmasi', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
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

    public function konfirmasi(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where([
            ['detail_pinjams.pinjam_id', $id],
            ['detail_pinjams.status', 0]
        ])
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.barang_id',
                'detail_pinjams.jumlah',
                'detail_pinjams.rusak',
                'detail_pinjams.hilang',
                'barangs.nama'
            )
            ->get();

        $tagihan = 0;

        foreach ($detail_pinjams as $detail_pinjam) {
            $jumlah = $request->jumlah[$detail_pinjam->id];
            if ($jumlah > 0) {
                $barang = Barang::where('id', $detail_pinjam->barang_id)->select('normal')->first();
                $tagihan_jumlah = 0;
                $tagihan_peminjaman = TagihanPeminjaman::where([
                    ['pinjam_id', $id],
                    ['detail_pinjam_id', $detail_pinjam->id]
                ])->select('jumlah')->get();
                if (count($tagihan_peminjaman)) {
                    foreach ($tagihan_peminjaman as $t) {
                        $tagihan_jumlah += $t->jumlah;
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
                Barang::where('id', $detail_pinjam->barang_id)->update([
                    'normal' => $barang->normal + $rusak_hilang,
                ]);
            } else {
                $tagihan += 1;
            }
        }

        if ($tagihan > 0) {
            $peminjaman_tamu_status = 'tagihan';
        } else {
            $peminjaman_tamu_status = 'selesai';
        }

        $peminjaman_tamu = Pinjam::where('id', $id)->update([
            'status' => $peminjaman_tamu_status
        ]);

        if ($peminjaman_tamu) {
            alert()->success('Success', 'Berhasil mengkonfirmasi tagihan');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi tagihan!');
        }

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
