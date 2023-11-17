<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;

class ProsesController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('status', 'disetujui')
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
                'matakuliah',
                'kategori',
                'status'
            )
            ->with('praktik:id,nama', 'ruang:id,nama', 'peminjam:id,nama')
            ->orderByDesc('id')
            ->get();

        return view('peminjam.farmasi.proses.index', compact('pinjams'));
    }

    public function show($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

        if ($kategori == 'normal') {
            return $this->show_mandiri($id);
        } else {
            return $this->show_estafet($id);
        }
    }

    public function show_mandiri($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
            ->first();
        $detailpinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah',
            )
            ->get();

        return view('peminjam.farmasi.proses.show_mandiri', compact('pinjam', 'detailpinjams'));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'jam_awal',
                'jam_akhir',
                'tanggal_awal',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
            ->first();
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah',
            )
            ->get();
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

        return view('peminjam.farmasi.proses.show_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok'));
    }

    public function edit($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

        if ($kategori == 'normal') {
            return $this->edit_mandiri($id);
        } else {
            return $this->edit_estafet($id);
        }
    }

    public function edit_mandiri($id, $data = null)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.id as ruang_id',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.bahan',
                'pinjams.kategori',
                'pinjams.status'
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id as id',
                'barangs.nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total',
            )
            ->get();
        // ->pluck('detail_pinjams.id')->toArray();

        // return in_array(999999, $detail_pinjams);

        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();
        $barangs = Barang::where('ruang_id', $pinjam->ruang_id)
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama'
            )
            ->orderBy('nama', 'ASC')
            ->get();

        return view('peminjam.farmasi.proses.edit_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'ruangs',
            'barangs',
            'data'
        ));
    }

    public function edit_estafet($id, $data = null)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.tanggal_awal',
                'praktiks.nama as praktik_nama',
                'ruangs.id as ruang_id',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.bahan',
                'pinjams.kategori',
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id as id',
                'barangs.nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total',
            )
            ->get();
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
        $ruang = Ruang::where('ruangs.id', $pinjam->ruang_id)
            ->join('users', 'ruangs.laboran_id', 'users.id')
            ->select(
                'ruangs.id',
                'ruangs.nama',
                'users.nama as laboran_nama'
            )->first();
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', auth()->user()->subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();
        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();
        $barangs = Barang::where('ruang_id', $ruang->id)
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama'
            )
            ->orderBy('nama', 'ASC')
            ->get();
        $pinjams = Pinjam::where([
            ['peminjam_id', '!=', auth()->user()->id],
            ['ruang_id', $pinjam->ruang_id],
            ['kategori', 'estafet'],
            ['status', '!=', 'selesai'],
            ['status', '!=', 'tagihan'],
        ])
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'users.kode as peminjam_kode',
                'users.nama as peminjam_nama',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir'
            )
            ->get();

        return view('peminjam.farmasi.proses.edit_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok', 'ruang', 'peminjams', 'ruangs', 'barangs', 'pinjams', 'data'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');
        if ($kategori == 'normal') {
            return $this->update_mandiri($request, $id);
        } elseif ($kategori == 'estafet') {
            return $this->update_estafet($request, $id);
        }
    }

    public function update_mandiri($request, $id)
    {
        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('id', $barang_id)->select('nama')->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_mandiri($id, $data);
        }

        if (count($error_barang) > 0) {
            return back()->withInput()
                ->with('data_items', $data_items)
                ->with('error_barang', $error_barang);
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

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }

    public function update_estafet($request, $id)
    {
        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('id', $barang_id)->select('nama')->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_estafet($id, $data);
        }

        if (count($error_barang) > 0) {
            return back()->withInput()
                ->with('data_items', $data_items)
                ->with('error_barang', $error_barang);
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

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }
}
