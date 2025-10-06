<?php

namespace App\Http\Controllers\Laboran\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\RekapBahan;
use App\Models\User;
use Illuminate\Http\Request;

class PengembalianController extends Controller
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

        return view('laboran.perawat.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select('praktik_id', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'disetujui') {
            return redirect('laboran/perawat')->with('error', 'Peminjaman tidak ditemukan!');
        }

        switch ($pinjam->praktik_id) {
            case 1:
                return $this->show_lab($id);
            case 2:
                return $this->show_kelas($id);
            case 3:
                return $this->show_luar($id);
            case 4:
                return $this->show_ruang($id);
            default:
                return back()->with('error', 'Jenis praktik tidak dikenali!');
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

        return view('laboran.perawat.pengembalian.show_lab', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
        ));
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

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
            ->get();

        return view('laboran.perawat.pengembalian.show_kelas', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'pinjam_detail_bahans',
        ));
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

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select('bahan_nama', 'prodi_nama', 'jumlah', 'satuan')
            ->get();

        return view('laboran.perawat.pengembalian.show_luar', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
        ));
    }

    public function update(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();

        $errors = array();
        $rusak = $request->rusak;
        $hilang = $request->hilang;
        foreach ($detail_pinjams as $detail_pinjam) {
            $nama = Barang::where('id', $detail_pinjam->barang_id)->value('nama');
            $jumlah = $rusak[$detail_pinjam->id] + $hilang[$detail_pinjam->id];
            if ($jumlah > $detail_pinjam->jumlah) {
                array_push($errors, '<strong>' . $nama . '</strong>, jumlah penambahan barang rusak dan hilang melebihi jumlah barang yang dipinjam!');
            }
        }

        if (count($errors)) {
            return back()->withInput()
                ->with('error', 'Gagal mengonfirmasi Pengembalian!')
                ->with('errors', $errors);
        }

        foreach ($detail_pinjams as $detail_pinjam) {
            $rusak_hilang = $rusak[$detail_pinjam->id] + $hilang[$detail_pinjam->id];
            $normal = $detail_pinjam->jumlah - $rusak_hilang;
            if ($rusak_hilang) {
                $detail_pinjam_status = false;
            } else {
                $detail_pinjam_status = true;
            }
            DetailPinjam::where('id', $detail_pinjam->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak[$detail_pinjam->id],
                    'hilang' => $hilang[$detail_pinjam->id],
                    'status' => $detail_pinjam_status
                ]);
        }

        $pinjam_detail_bahans = PinjamDetailBahan::select(
            'bahan_id',
            'bahan_nama',
            'prodi_id',
            'prodi_nama',
            'jumlah',
            'satuan'
        )
            ->where('pinjam_id', $id)
            ->get();

        $data = $pinjam_detail_bahans->map(function ($item) {
            return [
                'bahan_id'   => $item->bahan_id,
                'bahan_nama' => $item->bahan_nama,
                'prodi_id'   => $item->prodi_id,
                'prodi_nama' => $item->prodi_nama,
                'jumlah'     => $item->jumlah,
                'satuan'     => $item->satuan,
                'status'     => 'keluar',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        RekapBahan::insert($data);

        if (array_sum($rusak) + array_sum($hilang)) {
            $pinjam_status = 'tagihan';
        } else {
            $pinjam_status = 'selesai';
        }

        $pinjam = Pinjam::where('id', $id)->update([
            'status' => $pinjam_status
        ]);

        if (!$pinjam) {
            return back()->with('error', 'Gagal mengonfirmasi Pengembalian!');
        }

        return redirect('laboran/perawat/pengembalian')->with('success', 'Berhasil mengkonfirmasi peminjaman');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);

        $pinjam->detail_pinjams()->delete();
        $pinjam->pinjam_detail_bahans()->delete();
        $pinjam->kelompoks()->delete();
        $pinjam->forceDelete();

        return back()->with('success', 'Berhasil menghapus Peminjaman');
    }
}
