<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'selesai'],
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'selesai'],
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.praktik_id',
                'pinjams.ruang_id',
                'users.nama as user_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.keterangan',
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderBy('tanggal_awal', 'ASC')->orderBy('jam_awal', 'ASC')->get();

        return view('laboran.laporan.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->select('praktik_id')->first();

        if ($pinjam->praktik_id == 1) {
            return $this->show_laboratorium($id);
        } elseif ($pinjam->praktik_id == 2) {
            return $this->show_kelas($id);
        } elseif ($pinjam->praktik_id == 3) {
            return $this->show_luar($id);
        }
    }

    public function show_laboratorium($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.bahan'
            )
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
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('laboran.laporan.show_laboratorium', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
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
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('laboran.laporan.show_kelas', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id',  '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('laboran.laporan.show_luar', compact('pinjam', 'detail_pinjams'));
    }

    public function print()
    {
        $pinjams = Pinjam::where([
            ['laboran_id', auth()->user()->id],
            ['kategori', 'normal'],
            ['status', 'selesai'],
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'selesai'],
        ])->whereHas('ruang', function ($query) {
            $query->where('laboran_id', auth()->user()->id);
        })
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'matakuliah',
                'praktik as pinjam_praktik',
                'dosen',
                'kelas',
                'keterangan',
                'ruang_id',
                'laboran_id',
                'bahan',
                'status'
            )
            ->with('praktik:id,nama', 'peminjam:id,nama', 'ruang:id,nama', 'detail_pinjams')
            ->orderBy('id', 'ASC')->get();

        // return $pinjams;

        $pdf = Pdf::loadview('laboran.laporan.print', compact('pinjams'));

        return $pdf->stream('cetak_laporan');
    }
}
