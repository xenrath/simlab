<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $pinjam = Pinjam::select('kategori', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'disetujui') {
            return redirect('peminjam/farmasi')->with('error', 'Peminjaman tidak ditemukan!');
        }

        switch ($pinjam->kategori) {
            case 'normal':
                return $this->show_mandiri($id);
            case 'estafet':
                return $this->show_estafet($id);
            default:
                abort(404, 'Jenis praktik tidak ditemukan.');
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

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
            ->get();

        return view('peminjam.farmasi.proses.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans'
        ));
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

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
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

        return view('peminjam.farmasi.proses.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'data_kelompok'
        ));
    }

    public function edit($id)
    {
        $pinjam = Pinjam::select('kategori', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'disetujui') {
            return redirect('peminjam/farmasi')->with('error', 'Peminjaman tidak ditemukan!');
        }

        switch ($pinjam->kategori) {
            case 'normal':
                return $this->edit_mandiri($id);
            case 'estafet':
                return $this->edit_estafet($id);
            default:
                abort(404, 'Jenis praktik tidak ditemukan.');
        }
    }

    public function edit_mandiri($id)
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

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->with('barang:id,nama,ruang_id', 'barang.ruang:id,nama')
            ->get()
            ->map(function ($item) {
                return [
                    'detail_pinjam_id' => $item->id,
                    'id' => $item->barang->id,
                    'nama' => $item->barang->nama,
                    'ruang_id' => $item->barang->ruang_id,
                    'ruang' => [
                        'nama' => $item->barang->ruang->nama ?? '-',
                    ],
                    'jumlah' => $item->jumlah,
                ];
            });

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'pinjam_detail_bahan_id' => $item->id,
                    'id' => $item->bahan_id,
                    'nama' => $item->bahan_nama,
                    'prodi' => [
                        'id' => $item->prodi_id,
                        'nama' => $item->prodi_nama,
                    ],
                    'satuan_pinjam' => $item->satuan,
                    'jumlah' => $item->jumlah,
                ];
            });

        $ruangs = Ruang::where([
            ['prodi_id', auth()->user()->subprodi->prodi_id],
            ['is_praktik', true]
        ])
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->orderBy('nama')
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

        $bahans = Bahan::whereHas('ruang', function ($query) {
            $query->where('tempat_id', 2);
        })
            ->orWhere('prodi_id', 4)
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('peminjam.farmasi.proses.edit_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'ruangs',
            'barangs',
            'bahans',
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

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->with('barang:id,nama,ruang_id', 'barang.ruang:id,nama')
            ->get()
            ->map(function ($item) {
                return [
                    'detail_pinjam_id' => $item->id,
                    'id' => $item->barang->id,
                    'nama' => $item->barang->nama,
                    'ruang_id' => $item->barang->ruang_id,
                    'ruang' => [
                        'nama' => $item->barang->ruang->nama ?? '-',
                    ],
                    'jumlah' => $item->jumlah,
                ];
            });

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->get()
            ->map(function ($item) {
                return [
                    'pinjam_detail_bahan_id' => $item->id,
                    'id' => $item->bahan_id,
                    'nama' => $item->bahan_nama,
                    'prodi' => [
                        'id' => $item->prodi_id,
                        'nama' => $item->prodi_nama,
                    ],
                    'satuan_pinjam' => $item->satuan,
                    'jumlah' => $item->jumlah,
                ];
            });

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

        $subprodi_id = auth()->user()->subprodi_id;
        $nim = Str::substr(auth()->user()->kode, 0, 5);
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            ['kode', 'like', $nim . '%'],
        ])
            ->select('id', 'kode', 'nama')
            ->orderBy('kode')
            ->take(10)
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

        $bahans = Bahan::whereHas('ruang', function ($query) {
            $query->where('tempat_id', 2);
        })
            ->orWhere('prodi_id', 4)
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
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
            'detail_pinjams',
            'pinjam_detail_bahans',
            'data_kelompok',
            'ruang',
            'peminjams',
            'ruangs',
            'barangs',
            'bahans',
            'estafets',
        ));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'barangs' => 'required',
            'bahans.*.jumlah' => 'required|numeric|gt:0',
        ], [
            'barangs.required' => 'Barang belum ditambahkan!',
            'bahans.*.jumlah' => 'required|numeric|gt:0',
        ]);

        $old_barangs = array();
        if ($request->barangs) {
            foreach ($request->barangs as $value) {
                $barang = Barang::where('id', $value['id'])
                    ->select(
                        'nama',
                        'ruang_id',
                    )
                    ->with('ruang:id,nama')
                    ->first();
                array_push($old_barangs, array(
                    'id' => $value['id'],
                    'nama' => $barang->nama,
                    'ruang' => array('nama' => $barang->ruang->nama),
                    'jumlah' => $value['jumlah'],
                ));
            }
        }

        $old_bahans = array();
        if ($request->bahans) {
            foreach ($request->bahans as $value) {
                array_push($old_bahans, array(
                    'id' => $value['bahan_id'],
                    'nama' => $value['bahan_nama'],
                    'prodi' => array('id' => $value['prodi_id'], 'nama' => $value['prodi_nama']),
                    'jumlah' => $value['jumlah'],
                    'satuan_pinjam' => $value['satuan_pinjam'],
                ));
            }
        }

        if ($validator->fails()) {
            return back()->withInput()
                ->withErrors($validator->errors())
                ->with('old_barangs', $old_barangs)
                ->with('old_bahans', $old_bahans)
                ->with('error', 'Gagal memperbarui Peminjaman!');
        }

        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);

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

        foreach ($request->barangs as $value) {
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

        $bahan_request = $request->bahans ?? []; // kalau null jadi array kosong
        $bahan_ids = is_array($bahan_request) ? array_column($bahan_request, 'id') : [];

        $bahan_deleted = array_diff(
            PinjamDetailBahan::where('pinjam_id', $id)->pluck('bahan_id')->toArray(),
            $bahan_ids
        );

        if (!empty($bahan_deleted)) {
            PinjamDetailBahan::where('pinjam_id', $id)
                ->whereIn('bahan_id', $bahan_deleted)
                ->delete();
        }

        foreach ($request->input('bahans', []) as $value) {
            $pinjam_detail_bahan = PinjamDetailBahan::where([
                ['pinjam_id', $id],
                ['bahan_id', $value['bahan_id']]
            ])->exists();
            if ($pinjam_detail_bahan) {
                PinjamDetailBahan::where([
                    ['pinjam_id', $id],
                    ['bahan_id', $value['bahan_id']]
                ])->update([
                    'jumlah' => $value['jumlah']
                ]);
            } else {
                PinjamDetailBahan::create([
                    'pinjam_id'   => $id,
                    'bahan_id'    => $value['bahan_id'],
                    'bahan_nama'  => $value['bahan_nama'],
                    'prodi_id'    => $value['prodi_id'],
                    'prodi_nama'  => $value['prodi_nama'],
                    'jumlah'      => $value['jumlah'],
                    'satuan'      => $value['satuan_pinjam'],
                ]);
            }
        }

        return redirect('peminjam/farmasi/proses')->with('success', 'Berhasil memperbarui Peminjaman');
    }
}
