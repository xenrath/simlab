<?php

namespace App\Http\Controllers\Laboran\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where('status', 'menunggu')
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

        return view('laboran.farmasi.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select('kategori', 'status')->find($id);

        if (!$pinjam || $pinjam->status !== 'menunggu') {
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
            ['status', 'menunggu'],
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
                'dosen',
                'kategori',
                'status',
            )
            ->with(
                'peminjam:id,nama',
                'praktik:id,nama',
                'ruang:id,nama',
                'laboran:id,nama',
            )
            ->first();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
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

        return view('laboran.farmasi.peminjaman.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
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
            ->with(
                'peminjam:id,nama',
                'praktik:id,nama',
                'ruang:id,nama',
                'laboran:id,nama',
            )
            ->first();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
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

        return view('laboran.farmasi.peminjaman.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'pinjam_detail_bahans',
            'data_kelompok'
        ));
    }

    public function setujui($id)
    {
        Pinjam::where('id', $id)->update([
            'status' => 'disetujui',
        ]);

        return redirect('laboran/farmasi/peminjaman')->with('success', 'Berhasil menyetujui Peminjaman');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);

        $pinjam->detail_pinjams()->delete();
        $pinjam->pinjam_detail_bahans()->delete();
        $pinjam->kelompoks()->delete();
        $pinjam->forceDelete();

        return redirect('laboran/farmasi/peminjaman')->with('success', 'Berhasil menghapus Peminjaman');
    }
}
