<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PeminjamanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'menunggu'],
            ['peminjam_id', auth()->user()->id],
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])->get();

        return view('peminjam.peminjaman.index', compact('pinjams'));
    }

    public function create()
    {
        // if (!$this->check()) {
        //     alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
        //     return redirect("peminjam");
        // }

        $prodi = Prodi::where('nama', 'farmasi')->first();

        if (auth()->user()->prodi_id == $prodi->id) {
            $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)->orderBy('prodi', 'ASC')->orderBy('nama', 'ASC')->get();
            $barangs = Barang::where([
                ['tempat_id', '2'],
                ['stok', '>', '0']
            ])->orderBy('nama', 'ASC')->get();
            $bahans = Barang::where([
                ['tempat_id', 'farmasi'],
                ['stok', '>', '0'],
            ])->orderBy('nama', 'ASC')->get();
        } else {
            $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)->orderBy('nama', 'ASC')->get();
            $barangs = Barang::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '1');
            })->where('normal', '>', '0')->orderBy('ruang_id', 'ASC')->get();
            $bahans = Bahan::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '1');
            })->where('stok', '>', '0')->orderBy('nama', 'ASC')->get();
        }

        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            // ['telp', '!=', null],
            // ['alamat', '!=', null]
        ])->get();

        return view('peminjam.peminjaman.create', compact('ruangs', 'barangs', 'bahans', 'peminjams'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'matakuliah' => 'required',
            'dosen' => 'required',
            'ruang_id' => 'required',
            'keterangan' => 'required',
        ], [
            'tanggal_awal.required' => 'Tanggal pinjam harus diisi!',
            'tanggal_akhir.required' => 'Tanggal kembali harus diisi!',
            'jam_awal.required' => 'Jam pinjam harus diisi!',
            'jam_akhir.required' => 'Jam kembali harus diisi!',
            'matakuliah.required' => 'Mata kuliah harus diisi!',
            'dosen.required' => 'Dosen pengampu harus diisi!',
            'ruang_id.required' => 'Ruang Lab. harus dipilih!',
            'keterangan.required' => 'keterangan harus diisi!',
        ]);

        $tanggal_kembali = Carbon::parse($request->tanggal_kembali);
        $tanggal_pinjam = Carbon::parse($request->tanggal_pinjam)->addDays(5);

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if (!$barang_id) {
            alert()->error('Error', 'Pilih barang terlebih dahulu!');
            return redirect()->back();
        }

        $barangs = Barang::whereIn('id', $barang_id)->get();

        for ($i = 0; $i < count($barang_id); $i++) {
            $barang = $barangs->where('id', $barang_id[$i])->first();
            $sa = Satuan::where('id', $satuan[$i])->first();
            $kali = $barang->satuan->kali / $sa->kali;
            $js = $jumlah[$i] * $kali;

            // return $js;

            if ($js > $barang->normal) {
                alert()->error('Error!', 'Jumlah barang melebihi stok!');
                return back()->withInput();
            } else if ($tanggal_kembali > $tanggal_pinjam) {
                alert()->error('Error!', 'Maksimal peminjaman 5 Hari!');
                return back()->withInput();
            }
        }

        $pinjam = Pinjam::create(array_merge($request->all(), [
            'peminjam_id' => auth()->user()->id,
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]));

        if ($request->anggota) {
            $anggota = $request->anggota;
        } else {
            $anggota = null;
        }

        Kelompok::create(array_merge([
            'pinjam_id' => $pinjam->id,
            'ketua' => $request->ketua,
            'anggota' => $anggota,
        ]));

        for ($i = 0; $i < count($barang_id); $i++) {
            $barang = $barangs->where('id', $barang_id[$i])->first();
            $sa = Satuan::where('id', $satuan[$i])->first();
            $kali = $barang->satuan->kali / $sa->kali;
            $js = $jumlah[$i] * $kali;

            DetailPinjam::create(array_merge([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $barang->id,
                'jumlah' => $js,
                'satuan_id' => $sa->id
            ]));

            $stok = $barang->normal - $js;

            Barang::where('id', $barang->id)->update([
                'normal' => $stok
            ]);
        }

        alert()->success('Success', 'Berhasil mengajukan peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'menunggu'],
        ])->first();

        // if (!$pinjam) {
        //     abort(404);
        // }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('peminjam.peminjaman.show', compact('pinjam', 'detail_pinjams'));
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->forceDelete();
        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }
        if ($detailpinjams) {
            foreach ($detailpinjams as $detailpinjam) {
                $barang = Barang::where('id', $detailpinjam->barang_id)->first();

                $barang->update([
                    'normal' => $barang->normal + $detailpinjam->jumlah
                ]);
                
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }

    public function cetak($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['peminjam_id', auth()->user()->id],
            ['status', 'menunggu'],
        ])->first();

        // return response($pinjam);

        if (!$pinjam) {
            abort(404);
        }

        $barangs = DetailPinjam::where('pinjam_id', $pinjam->id)->get();

        $pdf = Pdf::loadview('peminjam.peminjaman.cetak', compact('pinjam', 'barangs'));

        return $pdf->stream('nota_peminjaman');
    }

    public function batal($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        if (!$pinjam) {
            abort(404);
        }

        $pinjam->update([
            'status' => 'dibatalkan'
        ]);

        alert()->success('Success', 'Berhasil membatalkan peminjaman');

        return back();
    }

    public function check()
    {
        if (
            auth()->user()->telp == null ||
            auth()->user()->alamat == null
        ) {
            return false;
        } else {
            return true;
        }
    }
}
