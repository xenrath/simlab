<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratbebasController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['peminjam_id', auth()->user()->id],
            ['status', 'disetujui'],
        ])->get();

        $detailpinjams = DetailPinjam::where('rusak', '>', '0')->whereHas('pinjam', function ($query) {
            $query->where('peminjam_id', auth()->user()->id);
        })->get();

        return view('peminjam.suratbebas', compact('pinjams', 'detailpinjams'));
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

        $user = auth()->user();
        $kalab = User::where('role', 'kalab')->first();

        $pdf = Pdf::loadview('peminjam.suratbebas-cetak', compact('user', 'kalab'));

        return $pdf->stream('surat_bebas');
    }
}
