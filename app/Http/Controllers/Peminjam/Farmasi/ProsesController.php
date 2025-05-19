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
use Illuminate\Support\Facades\Validator;

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
            ->with(
                'peminjam:id,nama',
                'praktik:id,nama',
                'ruang:id,nama',
                'laboran:id,nama'
            )
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

        return view('peminjam.farmasi.proses.show_mandiri', compact('pinjam', 'detail_pinjams'));
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
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
                'ruang_id',
                'laboran_id',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
            ->first();
        $old_barangs = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruang_id',
                'detail_pinjams.jumlah'
            )
            ->with('ruang:id,nama')
            ->get();
        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();
        $barangs = Barang::where('ruang_id', $pinjam->ruang_id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('peminjam.farmasi.proses.edit_mandiri', compact(
            'pinjam',
            'old_barangs',
            'ruangs',
            'barangs',
            'data'
        ));
    }

    public function edit_estafet($id, $data = null)
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
        $old_barangs = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruang_id',
                'detail_pinjams.jumlah'
            )
            ->with('ruang:id,nama')
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
        $barangs = Barang::where('ruang_id', $pinjam->ruang_id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        $estafets = Pinjam::where([
            ['peminjam_id', '!=', auth()->user()->id],
            ['ruang_id', $pinjam->ruang_id],
            ['kategori', 'estafet'],
            ['status', '!=', 'selesai'],
            ['status', '!=', 'tagihan'],
        ])
            ->select(
                'id',
                'peminjam_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir'
            )
            ->with('peminjam:id,kode,nama')
            ->get();

        return view('peminjam.farmasi.proses.edit_estafet', compact(
            'pinjam',
            'old_barangs',
            'data_kelompok',
            'ruang',
            'peminjams',
            'ruangs',
            'barangs',
            'estafets',
        ));
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
        $validator = Validator::make($request->all(), [
            'barangs' => 'required',
        ], [
            'barangs.required' => 'Barang belum ditambahkan!',
        ]);
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal membuat Peminjaman!');
            return back()->withInput()->withErrors($validator->errors())->with('old_barangs', array());
        }
        // 
        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);
        // 
        $barang_deleted = array_diff(
            DetailPinjam::where('pinjam_id', $id)->pluck('barang_id')->toArray(),
            array_column($request->barangs, 'id')
        );
        if (count($barang_deleted)) {
            foreach ($barang_deleted as $value) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $value]
                ])->delete();
            }
        }
        // 
        foreach ($request->barangs as $key => $value) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $value['id']]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $value['id']]
                ])->update([
                    'jumlah' => $value['jumlah']
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $value['id'],
                    'jumlah' => $value['jumlah'],
                    'satuan_id' => '6'
                ]);
            }
        }
        // 
        alert()->success('Success', 'Berhasil memperbarui Peminjaman');
        return redirect('peminjam/farmasi/proses');
    }

    public function update_estafet($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'barangs' => 'required',
        ], [
            'barangs.required' => 'Barang belum ditambahkan!',
        ]);
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Peminjaman!');
            return back()->withInput()->withErrors($validator->errors())->with('old_barangs', array());
        }
        // 
        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);
        // 
        $barang_deleted = array_diff(
            DetailPinjam::where('pinjam_id', $id)->pluck('barang_id')->toArray(),
            array_column($request->barangs, 'id')
        );
        if (count($barang_deleted)) {
            foreach ($barang_deleted as $value) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $value]
                ])->delete();
            }
        }
        // 
        foreach ($request->barangs as $key => $value) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $value['id']]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $value['id']]
                ])->update([
                    'jumlah' => $value['jumlah']
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $value['id'],
                    'jumlah' => $value['jumlah'],
                    'satuan_id' => '6'
                ]);
            }
        }
        // 
        alert()->success('Success', 'Berhasil memperbarui Peminjaman');
        return redirect('peminjam/farmasi/proses');
    }
}
