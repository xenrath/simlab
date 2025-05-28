<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class PengembalianNewController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'disetujui']
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

        return view('laboran.pengembalian-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->show_lab($id);
        } elseif ($praktik_id == 2) {
            return $this->show_kelas($id);
        } elseif ($praktik_id == 3) {
            return $this->show_luar($id);
        } elseif ($praktik_id == 4) {
            Pinjam::where('id', $id)->update([
                'status' => 'selesai'
            ]);

            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');

            return back();
        }
    }

    public function show_lab($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
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
        if ($kelompok) {
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
        } else {
            $data_kelompok = array();
        }
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select(
                'id',
                'jumlah',
                'barang_id'
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        // 
        return view('laboran.pengembalian-new.show_lab', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'keterangan',
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
        if ($kelompok) {
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
        } else {
            $data_kelompok = array();
        }
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select(
                'id',
                'jumlah',
                'barang_id'
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        // 
        return view('laboran.pengembalian-new.show_kelas', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'keterangan',
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select(
                'id',
                'jumlah',
                'barang_id'
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('laboran.pengembalian-new.show_luar', compact('pinjam', 'detail_pinjams'));
    }

    public function update(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();
        $errors = array();
        $rusak = $request->rusak;
        $hilang = $request->hilang;
        // 
        foreach ($detail_pinjams as $detail_pinjam) {
            $nama = Barang::where('id', $detail_pinjam->barang_id)->value('nama');
            // 
            $jumlah = $rusak[$detail_pinjam->id] + $hilang[$detail_pinjam->id];
            // 
            if ($jumlah > $detail_pinjam->jumlah) {
                array_push($errors, '<strong>' . $nama . '</strong>, jumlah penambahan barang rusak dan hilang melebihi jumlah barang yang dipinjam!');
            }
        }
        // 
        if (count($errors)) {
            return back()->withInput()->with('errors', $errors);
        }
        // 
        foreach ($detail_pinjams as $detail_pinjam) {
            $barang = Barang::where('id', $detail_pinjam->barang_id)->select('normal', 'rusak', 'hilang')->first();
            // 
            $rusak_hilang = $rusak[$detail_pinjam->id] + $hilang[$detail_pinjam->id];
            $normal = $detail_pinjam->jumlah - $rusak_hilang;
            // 
            if ($rusak_hilang) {
                $detail_pinjam_status = false;
            } else {
                $detail_pinjam_status = true;
            }
            // 
            Barang::where('id', $detail_pinjam->barang_id)->update([
                'normal' => $barang->normal - $rusak_hilang,
                'rusak' => $barang->rusak + $rusak[$detail_pinjam->id],
                'hilang' => $barang->hilang + $hilang[$detail_pinjam->id],
            ]);
            // 
            DetailPinjam::where('id', $detail_pinjam->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak[$detail_pinjam->id],
                    'hilang' => $hilang[$detail_pinjam->id],
                    'status' => $detail_pinjam_status
                ]);
        }
        // 
        if (array_sum($rusak) + array_sum($hilang)) {
            $pinjam_status = 'tagihan';
        } else {
            $pinjam_status = 'selesai';
        }
        // 
        $pinjam = Pinjam::where('id', $id)->update([
            'status' => $pinjam_status
        ]);
        // 
        if ($pinjam) {
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
        }
        // 
        return redirect('laboran/pengembalian-new');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $kelompok = Kelompok::where('pinjam_id', $id)->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->forceDelete();
        if ($kelompok) {
            $kelompok->delete();
        }
        if ($detail_pinjams) {
            foreach ($detail_pinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');
        return back();
    }

    public function konfirmasi($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            $pinjam = Pinjam::where('pinjams.id', $id)
                ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
                ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
                ->join('users', 'ruangs.laboran_id', '=', 'users.id')
                ->select(
                    'pinjams.id',
                    'pinjams.tanggal_awal',
                    'pinjams.jam_awal',
                    'pinjams.jam_akhir',
                    'praktiks.nama as praktik_nama',
                    'ruangs.nama as ruang_nama',
                    'users.nama as laboran_nama',
                    'pinjams.matakuliah',
                    'pinjams.praktik',
                    'pinjams.dosen',
                    'pinjams.kelas',
                    'pinjams.bahan'
                )
                ->first();
        } elseif ($praktik_id == 2) {
            $pinjam = Pinjam::where('pinjams.id', $id)
                ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
                ->join('users', 'pinjams.laboran_id', '=', 'users.id')
                ->select(
                    'pinjams.id',
                    'pinjams.tanggal_awal',
                    'pinjams.jam_awal',
                    'pinjams.jam_akhir',
                    'praktiks.nama as praktik_nama',
                    'users.nama as laboran_nama',
                    'pinjams.matakuliah',
                    'pinjams.praktik',
                    'pinjams.dosen',
                    'pinjams.kelas',
                    'pinjams.keterangan',
                    'pinjams.bahan'
                )
                ->first();
        } elseif ($praktik_id == 3) {
            $pinjam = Pinjam::where('pinjams.id', $id)
                ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
                ->join('users', 'pinjams.laboran_id',  '=', 'users.id')
                ->select(
                    'pinjams.id',
                    'pinjams.tanggal_awal',
                    'pinjams.tanggal_akhir',
                    'praktiks.nama as praktik_nama',
                    'users.nama as laboran_nama',
                    'pinjams.matakuliah',
                    'pinjams.praktik',
                    'pinjams.dosen',
                    'pinjams.kelas',
                    'pinjams.keterangan',
                    'pinjams.bahan'
                )
                ->first();
        }

        if ($praktik_id == 1 || $praktik_id == 2) {
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
        } else {
            $data_kelompok = null;
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.jumlah',
                'barangs.nama'
            )
            ->get();

        return view('laboran.pengembalian-new.konfirmasi', compact('praktik_id', 'pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function p_konfirmasi(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();

        $errors = array();
        $datas = array();

        foreach ($detail_pinjams as $detail_pinjam) {
            $barang = Barang::where('id', $detail_pinjam->barang_id)->select('nama')->first();

            $rusak = $request->input('rusak-' . $detail_pinjam->id);
            $hilang = $request->input('hilang-' . $detail_pinjam->id);

            $jumlah = $rusak + $hilang;

            $datas[$detail_pinjam->id] = array('rusak' => $rusak, 'hilang' => $hilang);

            if ($jumlah > $detail_pinjam->jumlah) {
                array_push($errors, '<strong>' . $barang->nama . '</strong>, jumlah penambahan barang rusak dan hilang melebihi jumlah barang yang dipinjam!');
            }
        }

        if (count($errors) > 0) {
            return back()->with('errors', $errors)->with('datas', $datas);
        }

        $tagihan = 0;

        foreach ($detail_pinjams as $detail_pinjam) {
            $barang = Barang::where('id', $detail_pinjam->barang_id)->select('normal', 'rusak', 'hilang')->first();

            $rusak = $request->input('rusak-' . $detail_pinjam->id);
            $hilang = $request->input('hilang-' . $detail_pinjam->id);

            $rusak_hilang = $rusak + $hilang;
            $normal = $detail_pinjam->total - $rusak_hilang;

            if ($rusak_hilang != 0) {
                $tagihan += 1;
                $detail_pinjam_status = false;
            } else {
                $detail_pinjam_status = true;
            }

            $barang_normal = $barang->normal - $rusak_hilang;
            $barang_rusak = $barang->rusak + $rusak;
            $barang_hilang = $barang->hilang + $hilang;

            Barang::where('id', $detail_pinjam->barang_id)->update([
                'normal' => $barang_normal,
                'rusak' => $barang_rusak,
                'hilang' => $barang_hilang,
            ]);

            DetailPinjam::where('id', $detail_pinjam->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak,
                    'hilang' => $hilang,
                    'status' => $detail_pinjam_status
                ]);
        }

        if ($tagihan > 0) {
            $pinjam_status = 'tagihan';
        } else {
            $pinjam_status = 'selesai';
        }

        $pinjam = Pinjam::where('id', $id)->update([
            'status' => $pinjam_status
        ]);

        if ($pinjam) {
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
        }

        return redirect('laboran/pengembalian-new');
    }

    public function feb($id)
    {
        Pinjam::where('id', $id)->update([
            'status' => 'selesai'
        ]);

        alert()->success('Success', 'Berhasil menyelesaikan Peminjaman');

        return back();
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
