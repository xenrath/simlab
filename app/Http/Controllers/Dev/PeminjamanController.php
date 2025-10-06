<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::select(
            'id',
            'peminjam_id',
            'praktik_id',
            'ruang_id',
            'laboran_id',
            'tanggal_awal',
            'tanggal_akhir',
            'jam_awal',
            'jam_akhir',
            'keterangan',
            'kategori',
            'status'
        )
            ->with([
                'peminjam:id,kode,nama,subprodi_id',
                'praktik:id,nama',
                'ruang:id,nama',
                'laboran:id,nama',
            ])
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('dev.peminjaman.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::select(
            'peminjam_id',
            'praktik_id',
            'ruang_id',
            'laboran_id',
            'tanggal_awal',
            'tanggal_akhir',
            'jam_awal',
            'jam_akhir',
            'matakuliah',
            'praktik as keterangan_praktik',
            'dosen',
            'kelas',
            'keterangan',
            'bahan',
            'kategori',
            'status'
        )
            ->with([
                'peminjam:id,nama,subprodi_id',
                'praktik:id,nama',
                'laboran:id,nama',
                'ruang:id,nama,laboran_id',
                'ruang.laboran:id,nama',
            ])
            ->findOrFail($id);

        // Ambil semua kelompok
        $kelompoks = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->get();

        // Kumpulkan semua kode user (ketua + anggota)
        $user_kodes = $kelompoks->flatMap(fn($k) => array_merge([$k->ketua], $k->anggota))->unique();

        // Ambil semua user sekali query
        $users = User::whereIn('kode', $user_kodes)->select('kode', 'nama')->get()->keyBy('kode');

        // Bentuk data kelompok
        $data_kelompok = $kelompoks->map(function ($kelompok) use ($users) {
            return [
                'ketua' => [
                    'kode' => $users[$kelompok->ketua]->kode,
                    'nama' => $users[$kelompok->ketua]->nama,
                ],
                'anggota' => collect($kelompok->anggota)->map(fn($kode) => [
                    'kode' => $users[$kode]->kode,
                    'nama' => $users[$kode]->nama,
                ])->all(),
            ];
        })->all();

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
            ->with([
                'barang:id,nama,ruang_id',
                'barang.ruang:id,nama',
            ])
            ->get();

        return view('dev.peminjaman.show', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);

        // Hapus semua kelompok
        Kelompok::where('pinjam_id', $id)->delete();

        // Ambil semua detail pinjam
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        if ($detailpinjams->isNotEmpty()) {
            if ($pinjam->status !== 'selesai') {
                foreach ($detailpinjams as $detailpinjam) {
                    $barang = Barang::find($detailpinjam->barang_id);
                    if ($barang) {
                        $barang->increment('normal', $detailpinjam->jumlah);
                    }
                }
            }

            // Hapus detail setelah update barang
            DetailPinjam::where('pinjam_id', $id)->delete();
        }

        $pinjam->delete();

        alert()->success('Success', 'Berhasil menghapus Peminjaman');
        return redirect('dev/peminjaman');
    }

    public function hapus_draft()
    {
        $pinjams = Pinjam::with(['kelompoks', 'detail_pinjams'])
            ->where('status', 'draft')
            ->withTrashed()
            ->get();

        foreach ($pinjams as $pinjam) {
            // Hapus semua relasi lewat collection delete
            $pinjam->kelompoks()->delete();
            $pinjam->detail_pinjams()->delete();

            // Hapus permanen pinjam
            $pinjam->forceDelete();
        }

        alert()->success('Success', 'Berhasil menghapus Draft Peminjaman');
        return back();
    }
}
