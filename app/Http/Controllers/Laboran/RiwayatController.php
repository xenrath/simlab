<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['pinjams.status', 'selesai'],
            ['pinjams.laboran_id', auth()->user()->id]
        ])
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->select(
                'pinjams.id',
                'praktiks.nama as praktik_nama',
                'users.nama as peminjam_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'ruangs.nama as ruang_nama',
                'pinjams.kategori',
            )
            ->get();

        return view('laboran.riwayat.index', compact('pinjams'));
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
            ['pinjams.id', $id],
            ['pinjams.laboran_id', auth()->user()->id],
            ['pinjams.status', 'selesai'],
        ])
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->select(
                'pinjams.id',
                'users.nama as peminjam_nama',
                'praktiks.nama as praktik_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'ruangs.nama as ruang_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.kategori',
                'pinjams.status'
            )
            ->first();

        if (!$pinjam) {
            abort(404);
        }

        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah'
            )
            ->get();

        return view('laboran.riwayat.show_mandiri', compact('pinjam', 'detail_pinjams'));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where([
            ['pinjams.id', $id],
            ['pinjams.laboran_id', auth()->user()->id],
            ['pinjams.status', 'selesai'],
            ])
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->select(
                'pinjams.id',
                'users.nama as peminjam_nama',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.tanggal_awal',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
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

        return view('laboran.riwayat.show_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok'));
    }

    public function destroy($id)
    {
        Pinjam::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
    }
}