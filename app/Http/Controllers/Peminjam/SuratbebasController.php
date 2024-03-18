<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class SuratbebasController extends Controller
{
    public function index()
    {
        $disetujuis = Pinjam::where('status', 'disetujui')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->count();
        $tagihans = Pinjam::where('status', 'tagihan')
            ->where(function ($query) {
                $query->where('peminjam_id', auth()->user()->id);
                $query->orWhereHas('kelompoks', function ($query) {
                    $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
                });
            })
            ->count();

        return view('peminjam.suratbebas', compact('disetujuis', 'tagihans'));
    }

    public function cetak()
    {
        $pinjams = Pinjam::where([
            ['peminjam_id', auth()->user()->id],
            ['status', 'disetujui'],
        ])->get();

        // $detailpinjams = DetailPinjam::where('rusak', '>', '0')->whereHas('pinjam', function ($query) {
        //     $query->where('peminjam_id', auth()->user()->id);
        // })->get();

        if (!$pinjams) {
            abort(404);
        }

        $user = User::where('id', auth()->user()->id)
            ->select(
                'kode',
                'nama',
                'subprodi_id'
            )
            ->with('subprodi:id,jenjang,nama')
            ->first();

        $pdf = Pdf::loadview('peminjam.suratbebas-cetak', compact('user'));

        return $pdf->stream('surat_bebas');
    }
}
