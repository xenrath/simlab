<?php

namespace App\Http\Controllers\Peminjam\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class MenungguController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('status', 'menunggu')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
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
                'kategori',
                'status'
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->simplePaginate(6);
        $total = Pinjam::where('status', 'menunggu')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })->count();

        return view('peminjam.perawat.menunggu.index', compact('pinjams', 'total'));
    }

    public function show($id)
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('peminjam.perawat.menunggu.show_lab', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_kelas($id)
    {
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('peminjam.perawat.menunggu.show_kelas', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_luar($id)
    {
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('peminjam.perawat.menunggu.show_luar', compact('pinjam', 'detail_pinjams'));
    }

    public function edit($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->edit_lab($id);
        } else if ($praktik_id == 2) {
            return $this->edit_kelas($id);
        } else if ($praktik_id == 3) {
            return $this->edit_luar($id);
        }
    }

    public function edit_lab($id, $data = null)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id',
                'barangs.nama as nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total'
            )
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('ruang_id', 'ASC')
            ->get();

        return view('peminjam.perawat.menunggu.edit_lab', compact('pinjam', 'data_kelompok', 'detail_pinjams', 'barangs', 'data'));
    }

    public function edit_kelas($id, $data = null)
    {
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id',
                'barangs.nama as nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total'
            )
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('ruang_id', 'ASC')
            ->get();

        return view('peminjam.perawat.menunggu.edit_kelas', compact('pinjam', 'data_kelompok', 'detail_pinjams', 'barangs', 'data'));
    }

    public function edit_luar($id, $data = null)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id', '=', 'users.id')
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id',
                'barangs.nama as nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total'
            )
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('ruang_id', 'ASC')
            ->get();

        return view('peminjam.perawat.menunggu.edit_luar', compact('pinjam', 'detail_pinjams', 'barangs', 'data'));
    }

    public function update(Request $request, $id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->update_lab($request, $id);
        } else if ($praktik_id == 2) {
            return $this->update_kelas($request, $id);
        } else if ($praktik_id == 3) {
            return $this->update_luar($request, $id);
        }
    }

    public function update_lab($request, $id)
    {
        $items = $request->items;
        $data = array();
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('barangs.id', $barang_id)
                    ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                    ->select(
                        'barangs.nama',
                        'ruangs.nama as ruang_nama'
                    )
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang_nama' => $barang->ruang_nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_lab($id, $data);
        }

        foreach ($items as $barang_id => $total) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $barang_id]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $barang_id]
                ])->update([
                    'jumlah' => $total
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $barang_id,
                    'jumlah' => $total,
                    'satuan_id' => '6'
                ]);
            }
        }

        alert()->success('Success', 'Berhasil memperbarui Peminjaman');

        return redirect('peminjam/perawat/menunggu');
    }

    public function update_kelas($request, $id)
    {
        $items = $request->items;
        $data = array();
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('barangs.id', $barang_id)
                    ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                    ->select(
                        'barangs.nama',
                        'ruangs.nama as ruang_nama'
                    )
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang_nama' => $barang->ruang_nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_kelas($id, $data);
        }

        foreach ($items as $barang_id => $total) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $barang_id]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $barang_id]
                ])->update([
                    'jumlah' => $total
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $barang_id,
                    'jumlah' => $total,
                    'satuan_id' => '6'
                ]);
            }
        }

        alert()->success('Success', 'Berhasil memperbarui Peminjaman');

        return redirect('peminjam/perawat/menunggu');
    }

    public function update_luar($request, $id)
    {
        $items = $request->items;
        $data = array();
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('barangs.id', $barang_id)
                    ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                    ->select(
                        'barangs.nama',
                        'ruangs.nama as ruang_nama'
                    )
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang_nama' => $barang->ruang_nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_luar($id, $data);
        }

        foreach ($items as $barang_id => $total) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $barang_id]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $barang_id]
                ])->update([
                    'jumlah' => $total
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $barang_id,
                    'jumlah' => $total,
                    'satuan_id' => '6'
                ]);
            }
        }

        alert()->success('Success', 'Berhasil memperbarui Peminjaman');

        return redirect('peminjam/perawat/menunggu');
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

        return redirect('peminjam/perawat/menunggu');
    }
}
