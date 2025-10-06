<?php

namespace App\Http\Controllers\Laboran\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\RekapBahan;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where('status', 'disetujui')
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

        return view('laboran.farmasi.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select('kategori', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'disetujui') {
            return redirect('laboran/farmasi')->with('error', 'Peminjaman tidak ditemukan!');
        }

        switch ($pinjam->kategori) {
            case 'normal':
                return $this->show_mandiri($id);
            case 'estafet':
                return $this->show_estafet($id);
            default:
                return back()->with('error', 'Jenis praktik tidak dikenali!');
        }
    }

    public function show_mandiri($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'disetujui']
        ])
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'jam_awal',
                'jam_akhir',
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
                'laboran:id,nama',
            )
            ->first();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'barang_id', 'jumlah')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select(
                'bahan_nama',
                'prodi_nama',
                'jumlah',
                'satuan'
            )
            ->get();

        return view('laboran.farmasi.pengembalian.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
        ));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'disetujui']
        ])
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'jam_awal',
                'jam_akhir',
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
                'laboran:id,nama',
            )
            ->first();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select(
                'id',
                'barang_id',
                'jumlah',
            )
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        $pinjam_detail_bahans = PinjamDetailBahan::where('pinjam_id', $id)
            ->select(
                'bahan_nama',
                'prodi_nama',
                'jumlah',
                'satuan'
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

        return view('laboran.farmasi.pengembalian.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'data_kelompok'
        ));
    }

    public function update(Request $request, $id)
    {
        $rusak = $request->rusak;
        $hilang = $request->hilang;
        $pelakus = $request->pelakus ?? array();

        $kategori = Pinjam::where('id', $id)->value('kategori');
        if ($kategori === 'estafet') {
            $detail_pinjam_ids = DetailPinjam::where('pinjam_id', $id)->pluck('id');
            $ids = $detail_pinjam_ids->filter(function ($detail_pinjam_id) use ($rusak, $hilang, $pelakus) {
                $is_rusak = $rusak[$detail_pinjam_id] ?? false;
                $is_hilang = $hilang[$detail_pinjam_id] ?? false;
                $ada_pelaku = array_key_exists($detail_pinjam_id, $pelakus);
                return ($is_rusak || $is_hilang) && !$ada_pelaku;
            })->values()->all();
            if (count($ids)) {
                return back()
                    ->withInput()
                    ->with('id', $ids)
                    ->with('error', 'Gagal mengonfirmasi Pengembalian! Mohon pilih pelaku untuk barang rusak/hilang.');
            }
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();

        $errors = array();
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
                    'pelakus' => in_array($detail_pinjam->id, array_keys($pelakus)) ? $pelakus[$detail_pinjam->id] : null,
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

        return redirect('laboran/farmasi/pengembalian')->with('success', 'Berhasil mengkonfirmasi peminjaman');
    }
}
