<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        if ($status != "") {
            $pinjams = Pinjam::where('status', $status)->orderBy('created_at')->paginate(10);    
        } else {
            $pinjams = Pinjam::orderBy('created_at')->paginate(10);
        }

        return view('dev.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();

        return view('dev.peminjaman.show', compact('pinjam', 'detailpinjams'));
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->delete();

        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }

        if ($detailpinjams) {
            if ($pinjam->status != 'selesai') {
                foreach ($detailpinjams as $detailpinjam) {
                    $barang = Barang::where('id', $detailpinjam->barang_id)->first();
                    $barang->update([
                        'normal' => $barang->normal + $detailpinjam->jumlah
                    ]);
                }
            }
            foreach ($detailpinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('dev/peminjaman');
    }
}
