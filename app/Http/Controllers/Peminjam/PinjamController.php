<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\BarangPinjam;
use App\Models\Pinjam;
use App\Models\Prodi;
use App\Models\Ruang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class PinjamController extends Controller
{
    public function index()
    {
        return redirect('peminjam');
    }

    public function indexKebidanan(Request $request)
    {
        $prodi = Prodi::where('nama', 'Kebidanan')->first();
        $barangs = Barang::where('kode_prodi', $prodi->kode)->orderBy('nama', 'ASC')->get();

        // $cari = $request->get('cari');
        // if ($cari != "") {
        //     $barangs = Barang::where('kode_prodi', $prodi->kode)
        //         ->where('kode', 'LIKE', "%$cari%")
        //         ->orWhere('nama', 'LIKE', "%$cari%")
        //         ->orderBy('nama', 'ASC')
        //         ->get();
        // } else {
        //     $barangs = Barang::where('kode_prodi', $prodi->kode)->orderBy('nama', 'ASC')->get();
        // }

        return view('peminjam.pinjam.index', compact('prodi', 'barangs'));
    }

    public function createKebidanan()
    {
        $prodi = Prodi::where('nama', 'Kebidanan')->first();
        $ruangs = Ruang::where('kode_prodi', $prodi->kode)->get();
        $barangs = Barang::where([
            ['kode_prodi', $prodi->kode],
            ['kategori', 'barang']
        ])->orderBy('nama', 'ASC')->get();
        $bahans = Barang::where([
            ['kode_prodi', $prodi->kode],
            ['kategori', 'bahan']
        ])->orderBy('nama', 'ASC')->get();

        return view('peminjam.pinjam.create', compact('prodi', 'ruangs', 'barangs', 'bahans'));
    }

    public function indexKeperawatan()
    {
        $prodi = Prodi::where('nama', 'Keperawatan')->first();
        $barangs = Barang::where('kode_prodi', $prodi->kode)->get();
        return view('pinjam.barang', compact('prodi', 'barangs'));
    }

    public function indexFarmasi()
    {
        $prodi = Prodi::where('nama', 'Farmasi')->first();
        $barangs = Barang::where('kode_prodi', $prodi->kode)->get();
        return view('pinjam.barang', compact('prodi', 'barangs'));
    }

    public function indexK3()
    {
        $prodi = Prodi::where('nama', 'K3')->first();
        $barangs = Barang::where('kode_prodi', $prodi->kode)->get();
        return view('pinjam.barang', compact('prodi', 'barangs'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
            'ruang_id' => 'required',
            'keperluan' => 'required',
            'barang_id' => 'required',
        ], [
            'tanggal_awal.required' => 'Tanggal pinjam harus diisi!',
            'tanggal_akhir.required' => 'Tanggal kembali harus diisi!',
            'jam_awal.required' => 'Jam pinjam harus diisi!',
            'jam_akhir.required' => 'Jam kembali harus diisi!',
            'ruang_id.required' => 'Ruang Lab. harus dipilih!',
            'keperluan.required' => 'Keperluan harus diisi!'
        ]);

        $tanggal_kembali = Carbon::parse($request->tanggal_kembali);
        $tanggal_pinjam = Carbon::parse($request->tanggal_pinjam)->addDays(5);

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;

        if (!$barang_id) {
            alert()->error('Error', 'Pilih barang terlebih dahulu!');
            return redirect()->back();
        }

        $barangs = Barang::whereIn('id', $barang_id)->get();

        for ($i = 0; $i < count($barang_id); $i++) {
            $barang = $barangs->where('id', $barang_id[$i])->first();
            if ($jumlah[$i] > $barang->stok) {
                Alert::error('Jumlah barang melebihi stok!', 'Error!')->prevent("OK");
                return redirect()->back();
            } else if ($jumlah[$i] < 1) {
                Alert::error('Masukan jumlah barang dengan benar!', 'Error!')->prevent("OK");
                return redirect()->back();
            } else if ($tanggal_kembali > $tanggal_pinjam) {
                Alert::error('Maksimal peminjaman 5 Hari!', 'Error!')->prevent("OK");
                return redirect()->back();
            }
        }

        $pinjam = Pinjam::create(array_merge($request->all(), [
            'peminjam_id' => auth()->user()->id,
            'status' => 'menunggu'
        ]));

        for ($i = 0; $i < count($barang_id); $i++) {
            $barang = $barangs->where('id', $barang_id[$i])->first();
            BarangPinjam::create(array_merge([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $barang->id,
                'jumlah' => $jumlah[$i]
            ]));
        }

        Alert::success('Success', 'Berhasil mengajukan peminjaman');
        return redirect('peminjam');
    }

    public function barangDipilih(Request $request)
    {
        // $keyword = $request->keyword;
        // $barangs = Barang::where('ruangan_id', $id)
        //     ->where('nama_barang', 'LIKE', '%' . $keyword . '%')
        //     ->orWhere('kode_barang', 'LIKE', '%' . $keyword . '%')
        //     ->paginate(10);
        // return response($output);

        $items = $request->items;
        $output = "";
        if ($items == null) {
            $output .=
                '<tr>
                    <td colspan="4" class="text-center">- Belum ada barang yang dipilih -</td>
                </tr>';
        } else {
            $barangs = Barang::whereIn('id', $items)->orderBy('nama', 'ASC')->get();
            $no = 1;
            for ($i = 0; $i < count($barangs); $i++) {
                $output .=
                    '<tr>
                        <td class="text-center">' . $no++ . '</td>
                        <td>' . $barangs[$i]->nama . '</td>
                        <td class="text-center">' . $barangs[$i]->stok . '</td>
                        <td>
                            <input type="number" name="jumlah[' . $i . ']" class="form-control" oninput="this.value = !!this.value && Math.abs(this.value) > 0 && !!this.value && Math.abs(this.value) <= ' . $barangs[$i]->stok . ' ? Math.abs(this.value) : null" required>
                            <input type="hidden" name="barang_id[' . $i . ']" value="' . $barangs[$i]->id . '" class="form-control">
                        </td>
                    </tr>';
            }
        }
        return response($output);
    }

    public function indexMenunggu()
    {
        $status = "menunggu";

        $pinjams = Pinjam::whereHas('ruang', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })->orderBy('id', 'DESC')->where('status', $status)->get();

        return view('pinjam.index', compact('pinjams', 'status'));
    }

    public function detail($id)
    {
        $pinjam = Pinjam::find($id);
        $barang_pinjams = BarangPinjam::where('pinjam_id', $pinjam->id)->get();

        return view('peminjam.pinjam.show', compact('pinjam', 'barang_pinjams'));
    }
}