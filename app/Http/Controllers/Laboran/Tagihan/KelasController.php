<?php

namespace App\Http\Controllers\Laboran\Tagihan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function show($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as peminjam_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('id', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where([
            ['detail_pinjams.pinjam_id', $id],
            ['detail_pinjams.status', 0]
        ])
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.jumlah',
                'detail_pinjams.rusak',
                'detail_pinjams.hilang',
                'barangs.nama as barang_nama',
            )
            ->get();

        $tagihan_peminjamans = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.jumlah',
                'barangs.nama',
                'tagihan_peminjamans.created_at'
            )
            ->get();

        $tagihan_group_by = TagihanPeminjaman::where('tagihan_peminjamans.pinjam_id', $id)
            ->join('detail_pinjams', 'tagihan_peminjamans.detail_pinjam_id', '=', 'detail_pinjams.id')
            ->select(
                'tagihan_peminjamans.id',
                'tagihan_peminjamans.detail_pinjam_id',
                'tagihan_peminjamans.jumlah'
            )
            ->get()
            ->groupBy('detail_pinjam_id');

        $tagihan_detail = array();

        foreach ($tagihan_group_by as $key => $value) {
            $jumlah = 0;

            foreach ($value as $v) {
                $jumlah += $v->jumlah;
            }

            $tagihan_detail[$key] = $jumlah;
        }

        return view('laboran.tagihan.kelas.show', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans',
            'tagihan_detail'
        ));
    }

    // public function konfirmasi(Request $request, $id)
    // {
    //     $detail_pinjams = DetailPinjam::where([
    //         ['detail_pinjams.pinjam_id', $id],
    //         ['detail_pinjams.status', 0]
    //     ])
    //         ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
    //         ->select(
    //             'detail_pinjams.id',
    //             'detail_pinjams.barang_id',
    //             'detail_pinjams.jumlah',
    //             'detail_pinjams.rusak',
    //             'detail_pinjams.hilang',
    //             'barangs.nama'
    //         )
    //         ->get();

    //     return $detail_pinjams;

    //     $tagihan = 0;

    //     foreach ($detail_pinjams as $detail_pinjam) {

    //         $jumlah = $request->jumlah[$detail_pinjam->id];

    //         if ($jumlah > 0) {

    //             $barang = Barang::where('id', $detail_pinjam->barang_id)->select('normal')->first();

    //             $tagihan_jumlah = 0;

    //             $tagihan_peminjaman = TagihanPeminjaman::where([
    //                 ['pinjam_id', $id],
    //                 ['detail_pinjam_id', $detail_pinjam->id]
    //             ])->get('jumlah');

    //             if (count($tagihan_peminjaman)) {
    //                 foreach ($tagihan_peminjaman as $t) {
    //                     $tagihan_jumlah += $t->jumlah;
    //                 }
    //             }

    //             TagihanPeminjaman::create([
    //                 'pinjam_id' => $id,
    //                 'pinjam_id' => $detail_pinjam->id,
    //                 'jumlah' => $jumlah
    //             ]);

    //             $rusak_hilang = $detail_pinjam->rusak + $detail_pinjam->hilang - $tagihan_jumlah;

    //             if ($jumlah != $rusak_hilang) {
    //                 $tagihan += 1;
    //                 $detail_pinjam_status = false;
    //             } else {
    //                 $detail_pinjam_status = true;
    //             }

    //             DetailPinjam::where('id', $detail_pinjam->id)->update([
    //                 'status' => $detail_pinjam_status
    //             ]);

    //             Barang::where('id', $detail_pinjam->barang_id)->update([
    //                 'normal' => $barang->normal + $rusak_hilang,
    //             ]);
    //         } else {
    //             $tagihan += 1;
    //         }
    //     }

    //     if ($tagihan > 0) {
    //         $peminjaman_tamu_status = 'tagihan';
    //     } else {
    //         $peminjaman_tamu_status = 'selesai';
    //     }

    //     $peminjaman_tamu = Pinjam::where('id', $id)->update([
    //         'status' => $peminjaman_tamu_status
    //     ]);

    //     if ($peminjaman_tamu) {
    //         alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
    //     } else {
    //         alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
    //     }

    //     return redirect('admin/peminjaman/tagihan');
    // }
}
