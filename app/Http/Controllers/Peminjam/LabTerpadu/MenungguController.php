<?php

namespace App\Http\Controllers\Peminjam\LabTerpadu;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MenungguController extends Controller
{
    public function index()
    {
        $user_id = auth()->id();
        $user_kode = auth()->user()->kode;

        $pinjams = Pinjam::where('status', 'menunggu')
            ->where(function ($query) use ($user_id, $user_kode) {
                $query->where('peminjam_id', $user_id)->orWhereHas('kelompoks', function ($query) use ($user_kode) {
                    $query->where('ketua', $user_kode)->orWhere('anggota', 'like', '%' . $user_kode . '%');
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
            ->with([
                'praktik:id,nama',
                'ruang:id,nama'
            ])
            ->orderByDesc('id')
            ->get();

        return view('peminjam.labterpadu.menunggu.index', compact('pinjams'));
    }

    public function show($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        switch ($praktik_id) {
            case 1:
                return $this->show_lab($id);
            case 2:
                return $this->show_kelas($id);
            case 3:
                return $this->show_luar($id);
            case 4:
                return $this->show_ruang($id);
            default:
                abort(404, 'Jenis praktik tidak ditemukan.');
        }
    }

    public function show_lab($id)
    {
        $pinjam = Pinjam::select(
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
            ->with([
                'praktik:id,nama',
                'laboran:id,nama',
                'ruang' => function ($query) {
                    $query->select('id', 'nama', 'laboran_id')
                        ->with('laboran:id,nama');
                }
            ])
            ->findOrFail($id);

        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $data_kelompok = [];

        if ($kelompok) {
            $ketua = User::select('kode', 'nama')->where('kode', $kelompok->ketua)->first();

            $anggota = collect($kelompok->anggota)->map(function ($kode) {
                return User::select('kode', 'nama')->where('kode', $kode)->first();
            })->filter()->map(function ($user) {
                return ['kode' => $user->kode, 'nama' => $user->nama];
            })->values();

            $data_kelompok = [
                'ketua' => ['kode' => $ketua->kode, 'nama' => $ketua->nama],
                'anggota' => $anggota
            ];
        }

        $detail_pinjams = DetailPinjam::select('jumlah', 'barang_id')
            ->where('pinjam_id', $id)
            ->with(['barang' => function ($query) {
                $query->select('id', 'nama', 'ruang_id')
                    ->with('ruang:id,nama');
            }])
            ->get();

        return view('peminjam.labterpadu.menunggu.show_lab', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::select(
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
            ->with([
                'praktik:id,nama',
                'laboran:id,nama'
            ])
            ->findOrFail($id);

        $kelompok = Kelompok::where('pinjam_id', $id)
            ->select('ketua', 'anggota')
            ->first();

        $data_kelompok = [];

        if ($kelompok) {
            $ketua = User::select('kode', 'nama')
                ->where('kode', $kelompok->ketua)
                ->first();

            $anggota = collect($kelompok->anggota)->map(function ($kode) {
                return User::select('kode', 'nama')
                    ->where('kode', $kode)
                    ->first();
            })->filter()->map(function ($user) {
                return [
                    'kode' => $user->kode,
                    'nama' => $user->nama
                ];
            })->values();

            $data_kelompok = [
                'ketua' => [
                    'kode' => $ketua->kode,
                    'nama' => $ketua->nama
                ],
                'anggota' => $anggota
            ];
        }

        $detail_pinjams = DetailPinjam::select('jumlah', 'barang_id')
            ->where('pinjam_id', $id)
            ->with([
                'barang' => function ($query) {
                    $query->select('id', 'nama', 'ruang_id')
                        ->with('ruang:id,nama');
                }
            ])
            ->get();

        return view('peminjam.labterpadu.menunggu.show_kelas', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::select(
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
            ->with([
                'praktik:id,nama',
                'laboran:id,nama'
            ])
            ->findOrFail($id);

        $detail_pinjams = DetailPinjam::select('jumlah', 'barang_id')
            ->where('pinjam_id', $id)
            ->with([
                'barang' => function ($query) {
                    $query->select('id', 'nama', 'ruang_id')
                        ->with('ruang:id,nama');
                }
            ])
            ->get();

        return view('peminjam.labterpadu.menunggu.show_luar', compact('pinjam', 'detail_pinjams'));
    }

    public function show_ruang($id)
    {
        $pinjam = Pinjam::select(
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
            ->with([
                'praktik:id,nama',
                'laboran:id,nama',
                'ruang' => function ($query) {
                    $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
                }
            ])
            ->findOrFail($id);

        $kelompok = Kelompok::where('pinjam_id', $id)
            ->select('ketua', 'anggota')
            ->first();

        $data_kelompok = [];

        if ($kelompok) {
            $ketua = User::where('kode', $kelompok->ketua)
                ->select('kode', 'nama')
                ->first();

            $anggota = User::whereIn('kode', $kelompok->anggota)
                ->select('kode', 'nama')
                ->get()
                ->map(function ($user) {
                    return ['kode' => $user->kode, 'nama' => $user->nama];
                })
                ->toArray();

            $data_kelompok = [
                'ketua' => ['kode' => $ketua->kode, 'nama' => $ketua->nama],
                'anggota' => $anggota
            ];
        }

        return view('peminjam.labterpadu.menunggu.show_ruang', compact('pinjam', 'data_kelompok'));
    }

    public function edit($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->edit_lab($id);
        } elseif ($praktik_id == 2) {
            return $this->edit_kelas($id);
        } elseif ($praktik_id == 3) {
            return $this->edit_luar($id);
        }
    }

    public function edit_lab($id)
    {
        $pinjam = Pinjam::select(
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
            ->with([
                'praktik:id,nama',
                'laboran:id,nama',
                'ruang' => fn($query) => $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama')
            ])
            ->findOrFail($id);

        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $data_kelompok = [];

        if ($kelompok) {
            $ketua = User::select('kode', 'nama')
                ->where('kode', $kelompok->ketua)
                ->first();

            $anggota = User::select('kode', 'nama')
                ->whereIn('kode', $kelompok->anggota)
                ->get()
                ->map(fn($user) => ['kode' => $user->kode, 'nama' => $user->nama])
                ->toArray();

            $data_kelompok = [
                'ketua' => ['kode' => $ketua->kode, 'nama' => $ketua->nama],
                'anggota' => $anggota
            ];
        }

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

        $barangs = Barang::whereHas('ruang', fn($q) => $q->where('tempat_id', 1))
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('peminjam.labterpadu.menunggu.edit_lab', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'barangs'
        ));
    }

    public function edit_kelas($id)
    {
        $pinjam = Pinjam::select(
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
            ->with([
                'praktik:id,nama',
                'laboran:id,nama',
            ])
            ->findOrFail($id);

        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $data_kelompok = [];

        if ($kelompok) {
            $ketua = User::select('kode', 'nama')
                ->where('kode', $kelompok->ketua)
                ->first();

            $anggota = User::select('kode', 'nama')
                ->whereIn('kode', $kelompok->anggota)
                ->get()
                ->map(fn($user) => ['kode' => $user->kode, 'nama' => $user->nama])
                ->toArray();

            $data_kelompok = [
                'ketua' => ['kode' => $ketua->kode, 'nama' => $ketua->nama],
                'anggota' => $anggota,
            ];
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->with('barang:id,nama,ruang_id', 'barang.ruang:id,nama')
            ->get()
            ->map(function ($item) {
                return [
                    'detail_pinjam_id' => $item->id,
                    'id' => $item->barang->id,
                    'nama' => $item->barang->nama,
                    'ruang_id' => $item->barang->ruang_id,
                    'ruang' => $item->barang->ruang->nama ?? '-',
                    'jumlah' => $item->jumlah,
                ];
            });

        return $detail_pinjams;

        $barangs = Barang::whereHas('ruang', fn($q) => $q->where('tempat_id', 1))
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('peminjam.labterpadu.menunggu.edit_kelas', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'barangs'
        ));
    }

    public function edit_luar($id)
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
            ->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', 'barangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id',
                'barangs.nama',
                'barangs.ruang_id',
                'detail_pinjams.jumlah'
            )
            ->with('ruang:id,nama')
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        // 
        return view('peminjam.labterpadu.menunggu.edit_luar', compact(
            'pinjam',
            'detail_pinjams',
            'barangs'
        ));
    }

    public function update(Request $request, $id)
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
        return redirect('peminjam/labterpadu/menunggu');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        // 
        $pinjam->detail_pinjams()->delete();
        $pinjam->kelompoks()->delete();
        $pinjam->forceDelete();
        // 
        alert()->success('Success', 'Berhasil menghapus peminjaman');
        return back();
    }
}
