<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function list_peminjaman($nim = null)
    {
        if ($nim != null) {
            $peminjamans = Pinjam::select(
                'pinjams.peminjam_id',
                'pinjams.tanggal_awal',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.keterangan',
                'pinjams.status',
                'users.kode as kode_peminjam',
                'users.nama as nama_peminjam',
                'ruangs.nama as nama_ruang',
                'praktiks.nama as nama_praktik'
            )
                ->join('users', 'users.id', '=', 'pinjams.peminjam_id')
                ->join('praktiks', 'praktiks.id', '=', 'pinjams.praktik_id')
                ->join('ruangs', 'ruangs.id', '=', 'pinjams.ruang_id')
                ->whereHas('peminjam', function ($query) use ($nim) {
                    $query->where('kode', $nim);
                })->get();
        } else {
            $peminjamans = Pinjam::select(
                'pinjams.peminjam_id',
                'pinjams.tanggal_awal',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.keterangan',
                'pinjams.status',
                'users.kode as kode_peminjam',
                'users.nama as nama_peminjam',
                'ruangs.nama as nama_ruang',
                'praktiks.nama as nama_praktik'
            )
                ->join('users', 'users.id', '=', 'pinjams.peminjam_id')
                ->join('praktiks', 'praktiks.id', '=', 'pinjams.praktik_id')
                ->join('ruangs', 'ruangs.id', '=', 'pinjams.ruang_id')
                ->get();
        }

        if (count($peminjamans) > 0) {
            return response()->json(['data' => $peminjamans]);
        } else {
            return response()->json(['data' => null]);
        }
    }
}
