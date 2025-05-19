<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\Tempat;
use App\Models\User;
use Illuminate\Http\Request;

class PengembalianController extends Controller
{
    public function index(Request $request)
    {
        if (!$this->check_user()) {
            return redirect('/');
        }
        // 
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

        return view('laboran.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
    {
        if (!$this->check_user()) {
            return redirect('/');
        }
        // 
        $kategori = Pinjam::where('id', $id)->value('kategori');
        // 
        if ($kategori == 'normal') {
            return $this->show_mandiri($id);
        } elseif ($kategori == 'estafet') {
            return $this->show_estafet($id);
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

        if (!$pinjam) {
            abort(404);
        }

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

        return view('laboran.pengembalian.show_mandiri', compact('pinjam', 'detail_pinjams'));
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

        if (!$pinjam) {
            abort(404);
        }

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

        return view('laboran.pengembalian.show_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok'));
    }

    // Konfirmasi Mandiri
    public function update_mandiri(Request $request, $id)
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
        return redirect('laboran/pengembalian');
    }

    // Konfirmasi Estafet
    public function update_estafet(Request $request, $id)
    {
        $rusak = $request->rusak;
        $hilang = $request->hilang;
        $pelakus = $request->pelakus ?? array();
        // 
        $ids = array();
        $detail_pinjam_ids = DetailPinjam::where('pinjam_id', $id)->pluck('id');
        foreach ($detail_pinjam_ids as $detail_pinjam_id) {
            if ($rusak[$detail_pinjam_id] || $hilang[$detail_pinjam_id]) {
                if (!array_key_exists($detail_pinjam_id, $pelakus)) {
                    array_push($ids, $detail_pinjam_id);
                }
            }
        }
        //
        if (count($ids)) {
            alert()->error('Error', 'Gagal mengonfirmasi Pengembalian!');
            return back()->withInput()->with('id', $ids);
        }
        // 
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();
        // 
        $errors = array();
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
            $barang_normal = $barang->normal - $rusak_hilang;
            $barang_rusak = $barang->rusak + $rusak[$detail_pinjam->id];
            $barang_hilang = $barang->hilang + $hilang[$detail_pinjam->id];

            Barang::where('id', $detail_pinjam->barang_id)->update([
                'normal' => $barang_normal,
                'rusak' => $barang_rusak,
                'hilang' => $barang_hilang,
            ]);

            DetailPinjam::where('id', $detail_pinjam->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak[$detail_pinjam->id],
                    'hilang' => $hilang[$detail_pinjam->id],
                    'pelakus' => in_array($detail_pinjam->id, array_keys($pelakus)) ? $pelakus[$detail_pinjam->id] : null,
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
        return redirect('laboran/pengembalian');
    }

    public function check_user()
    {
        $ruang = Ruang::where('laboran_id', auth()->user()->id)->first();
        if ($ruang->tempat_id == '2') {
            return true;
        } else {
            return false;
        }
    }
}
