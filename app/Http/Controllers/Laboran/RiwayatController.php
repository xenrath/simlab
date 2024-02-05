<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
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
            ['id', $id],
            ['status', 'selesai'],
        ])
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'dosen',
            )
            ->first();
        if (!$pinjam) {
            abort(404);
        }
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
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

        return view('laboran.riwayat.show_mandiri', compact(
            'pinjam',
            'detail_pinjams',
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
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
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

        return view('laboran.riwayat.show_estafet', compact(
            'pinjam',
            'detail_pinjams',
            'data_kelompok',
            'tagihan_peminjamans',
        ));
    }

    public function destroy($id)
    {
        Pinjam::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
    }
}
