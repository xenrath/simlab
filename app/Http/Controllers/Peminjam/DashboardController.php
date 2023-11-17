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
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $peminjaman = Pinjam::where([
            ['status', 'menunggu'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        $pengembalian = Pinjam::where([
            ['status', 'disetujui'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        $riwayat = Pinjam::where([
            ['status', '!=', 'menunggu'],
            ['status', '!=', 'disetujui'],
            ['peminjam_id', auth()->user()->id],
        ])->count();

        return view('peminjam.index', compact(
            'peminjaman',
            'pengembalian',
            'riwayat',
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

    public function search_items(Request $request)
    {
        $keyword = $request->keyword;
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->where('nama', 'like', "%$keyword%")->select('id', 'nama', 'ruang_id')->with('ruang:id,nama')->get();

        return $barangs;
    }

    public function search(Request $request)
    {
        return 'hello';
        $keyword = $request->keyword;
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->where('nama', 'like', "%$keyword%")->select('id', 'nama')->get();

        return $barangs;
    }

    public function add_item($id)
    {
        $barang = Barang::where('barangs.id', $id)
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama'
            )->first();

        return $barang;
    }

    public function get_estafet($id)
    {
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total'
            )
            ->get();

        return $detail_pinjams;
    }

    public function delete_item($id)
    {
        if (DetailPinjam::where('id', $id)->exists()) {
            $detail_pinjam = DetailPinjam::findOrFail($id);
            $detail_pinjam->delete();
        }
        
        return true;
    }

    public function search_anggotas(Request $request)
    {
        $keyword = $request->keyword;
        $subprodi_id = auth()->user()->subprodi_id;
        $users = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            ['nama', 'like', "%$keyword%"]
        ])
            ->orWhere([
                ['id', '!=', auth()->user()->id],
                ['role', 'peminjam'],
                ['subprodi_id', $subprodi_id],
                ['kode', 'like', "%$keyword%"]
            ])
            ->select('id', 'kode', 'nama')
            ->get();

        return $users;
    }

    public function add_anggota($id)
    {
        $user = User::where('id', $id)->select('id', 'kode', 'nama')->first();

        return $user;
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
