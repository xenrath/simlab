<?php

namespace App\Http\Controllers\Laboran\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\Ruang;
use App\Models\TagihanPeminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where('status', 'selesai')
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
            ->paginate(10);

        return view('laboran.farmasi.riwayat.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select('kategori', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'selesai') {
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
            ['status', 'selesai'],
        ])
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'bahan',
                'dosen',
            )
            ->with('peminjam:id,nama')
            ->with('praktik:id,nama')
            ->with('ruang:id,nama')
            ->with('laboran:id,nama')
            ->first();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
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

        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();

        return view('laboran.farmasi.riwayat.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'tagihan_peminjamans'
        ));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'selesai'],
        ])
            ->select(
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'jam_awal',
                'jam_akhir',
                'tanggal_awal',
                'matakuliah',
                'dosen',
                'bahan',
                'laboran_id',
            )
            ->with('peminjam:id,nama')
            ->with('praktik:id,nama')
            ->with('ruang:id,nama')
            ->with('laboran:id,nama')
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
            ->select('jumlah', 'barang_id')
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

        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();

        return view('laboran.farmasi.riwayat.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'data_kelompok',
            'tagihan_peminjamans',
        ));
    }
}
