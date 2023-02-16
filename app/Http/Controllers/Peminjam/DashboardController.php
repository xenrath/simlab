<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\BarangPinjam;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Pinjam::where([
            ['status', 'menunggu'],
            ['peminjam_id', auth()->user()->id],
        ])->get();

        $pengembalian = Pinjam::where([
            ['status', 'disetujui'],
            ['peminjam_id', auth()->user()->id],
        ])->get();

        $riwayat = Pinjam::where([
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui'],
            ['peminjam_id', auth()->user()->id],
        ])->get();

        $keybarang = $request->get('keybarang');
        $keybahan = $request->get('keybahan');

        if ($keybarang != "") {
            $barangs = Barang::where('nama', 'LIKE', "%$keybarang%")->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $barangs = Barang::orderBy('nama', 'ASC')->paginate(10);
        }

        if ($keybahan != "") {
            $bahans = Bahan::where('nama', 'LIKE', "%$keybahan%")->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $bahans = Bahan::orderBy('nama', 'ASC')->paginate(10);
        }

        return view('peminjam.index', compact(
            'peminjaman',
            'pengembalian',
            'riwayat',
            'barangs',
            'bahans'
        ));
    }

    public function pinjam()
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data terlebih dahulu!');
            return back();
        }

        $prodi = Prodi::where('nama', 'farmasi')->first();

        if (auth()->user()->prodi_id == $prodi->id) {
            $ruangs = Ruang::where('tempat_id', '2')->orderBy('prodi', 'ASC')->orderBy('nama', 'ASC')->get();
            $barangs = Barang::where([
                ['tempat_id', '2'],
                ['stok', '>', '0']
            ])->orderBy('nama', 'ASC')->get();
            $bahans = Barang::where([
                ['tempat_id', 'farmasi'],
                ['stok', '>', '0'],
            ])->orderBy('nama', 'ASC')->get();
        } else {
            $ruangs = Ruang::where([
                ['tempat_id', '1'],
                ['kode', '!=', '01'],
                ['kode', '!=', '02']
            ])->orderBy('nama', 'ASC')->get();
            $barangs = Barang::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '1');
            })->where('normal', '>', '0')->orderBy('nama', 'ASC')->get();
            $bahans = Bahan::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '1');
            })->where('stok', '>', '0')->orderBy('nama', 'ASC')->get();
        }

        return view('peminjam.pinjam', compact('ruangs', 'barangs', 'bahans'));
    }

    public function pilih(Request $request)
    {
        $items = $request->items;

        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan', 'ruang')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }

    public function proses(Request $request)
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
            'keterangan.required' => 'keterangan harus diisi!'
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
        }

        alert()->success('Success', 'Berhasil mengajukan peminjaman');

        return redirect('peminjam/peminjaman');
    }

    public function show($id)
    {
        $pinjam = Pinjam::find($id);
        $barang_pinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();

        return view('peminjam.pinjam.show', compact('pinjam', 'barang_pinjams'));
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
