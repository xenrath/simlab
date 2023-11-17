<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['pinjams.status', 'menunggu'],
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

        return view('laboran.peminjaman.index', compact('pinjams'));
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
            ['pinjams.status', 'menunggu'],
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

        return view('laboran.peminjaman.show_mandiri', compact('pinjam', 'detail_pinjams'));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
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

        return view('laboran.peminjaman.show_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok'));
    }

    public function setujui($id)
    {
        Pinjam::where('id', $id)->update([
            'status' => 'disetujui',
        ]);

        alert()->success('Success', 'Berhasil menyetujui peminjaman');

        return redirect('laboran/peminjaman');
    }

    public function tolak($id)
    {
        $pinjam = Pinjam::whereHas('ruang', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->where('id', $id)->first();

        if (!$pinjam) {
            abort(404);
        }

        $tolak = $pinjam->update([
            'status' => 'ditolak',
            'laboran_id' => auth()->user()->id
        ]);

        if ($tolak) {
            alert()->success('Success', 'Berhasil menolak peminjaman');
        } else {
            alert()->error('Error!', 'Berhasil menolak peminjaman');
        }

        return redirect('laboran/peminjaman');
    }
}
