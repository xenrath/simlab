<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function list_peminjaman($nim = null)
    {
        if (is_null($nim)) {
            $peminjamans = Pinjam::select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'dosen',
                'kelas',
                'keterangan',
                'bahan',
                'kategori',
                'status',
            )->with('peminjam:id,kode,nama', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
                ->with('detail_pinjams', function ($query) {
                    $query->select('pinjam_id', 'barang_id')->with('barang', function ($query) {
                        $query->select('id', 'nama');
                    });
                })->get();
        } else {
            $user = User::where('kode', $nim)->select('id', 'kode')->first();
            $peminjamans = Pinjam::select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'dosen',
                'kelas',
                'keterangan',
                'bahan',
                'kategori',
                'status',
            )
                ->with('peminjam:id,kode,nama', 'praktik:id,nama', 'ruang:id,nama', 'laboran:id,nama')
                ->with('detail_pinjams', function ($query) {
                    $query->select('pinjam_id', 'barang_id')->with('barang', function ($query) {
                        $query->select('id', 'nama');
                    });
                })
                ->where('peminjam_id', $user->id)
                ->get();
        }

        if ($peminjamans) {
            return response()->json(['data' => $peminjamans]);
        } else {
            return response()->json(['data' => null]);
        }
    }
}
