<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        if (!$this->check_user()) {
            return redirect('/');
        }
        // 
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

        return view('laboran.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        if (!$this->check_user()) {
            return redirect('/');
        }
        // 
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

        alert()->success('Success', 'Berhasil menyetujui Peminjaman');
        return redirect('laboran/peminjaman');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $kelompok = Kelompok::where('pinjam_id', $id)->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();
        // 
        if ($kelompok) {
            $kelompok->delete();
        }
        // 
        if ($detail_pinjams) {
            foreach ($detail_pinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }
        // 
        $pinjam->forceDelete();
        // 
        alert()->success('Success', 'Berhasil menghapus Peminjaman');
        return redirect('laboran/peminjaman');
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
