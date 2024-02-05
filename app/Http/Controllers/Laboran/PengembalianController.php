<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
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

        return view('laboran.pengembalian.index', compact('pinjams'));
    }

    public function show($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

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
                'jam_awal',
                'jam_akhir',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
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
                'jam_awal',
                'jam_akhir',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
                'bahan',
                'kategori',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
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

    // Konfirmasi
    public function update(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();

        $errors = array();
        $datas = array();

        foreach ($detail_pinjams as $detail_pinjam) {
            $nama = Barang::where('id', $detail_pinjam->barang_id)->value('nama');

            $rusak = $request->input('rusak-' . $detail_pinjam->id);
            $hilang = $request->input('hilang-' . $detail_pinjam->id);

            $jumlah = $rusak + $hilang;

            $datas[$detail_pinjam->id] = array('rusak' => $rusak, 'hilang' => $hilang);

            if ($jumlah > $detail_pinjam->jumlah) {
                array_push($errors, '<strong>' . $nama . '</strong>, jumlah penambahan barang rusak dan hilang melebihi jumlah barang yang dipinjam!');
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
            $normal = $detail_pinjam->jumlah - $rusak_hilang;

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

        return redirect('laboran/pengembalian');
    }
}
