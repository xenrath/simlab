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
use Illuminate\Support\Facades\Validator;

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

        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)->orderBy('kode', 'ASC')->orderBy('nama', 'ASC')->get();
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '2');
        })->orderBy('ruang_id', 'ASC')->get();

        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            // ['telp', '!=', null],
            // ['alamat', '!=', null]
        ])->get();

        return view('peminjam.peminjaman.create', compact('ruangs', 'barangs', 'peminjams'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'matakuliah' => 'required',
            'dosen' => 'required',
            'ruang_id' => 'required',
        ], [
            'matakuliah.required' => 'Mata kuliah harus diisi!',
            'dosen.required' => 'Dosen pengampu harus diisi!',
            'ruang_id.required' => 'Ruang Lab. harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if (!$barang_id) {
            alert()->error('Error', 'Pilih barang terlebih dahulu!');
            return back()->withInput();
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
            }
        }

        $tanggal_awal = Carbon::now()->format('Y-m-d');
        $tanggal_akhir = Carbon::now()->addDays(7)->format('Y-m-d');

        $pinjam = Pinjam::create(array_merge($request->all(), [
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '1',
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'kategori' => 'normal',
            'status' => 'menunggu'
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

        alert()->success('Success', 'Berhasil membuat Peminjaman');

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

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('peminjam.peminjaman.show', compact('pinjam', 'detailpinjams'));
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
