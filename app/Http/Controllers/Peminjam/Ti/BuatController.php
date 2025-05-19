<?php

namespace App\Http\Controllers\Peminjam\Ti;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuatController extends Controller
{
    public function index()
    {
        return view('peminjam.ti.buat.index');
    }

    public function create()
    {
        // if (!$this->check()) {
        //     alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
        //     return redirect('peminjam/ti');
        // }

        // if (!$this->jam_kerja()) {
        //     alert()->error('Error!', 'Anda sedang tidak dalam waktu kerja!');
        //     return back();
        // }

        $praktik_id = request()->get('praktik_id');

        if ($praktik_id == '1') {
            return $this->create_lab();
        } elseif ($praktik_id == '3') {
            return $this->create_luar();
        } else {
            alert()->error('Gagal!', 'Kategori peminjaman tidak ditemukan!');
            return back();
        }
    }

    public function create_lab()
    {
        $ruangs = Ruang::where([
            ['tempat_id', '1'],
            ['kode', '!=', '01'],
            ['kode', '!=', '02'],
            ['prodi_id', '7']
        ])
            ->select('id', 'prodi_id', 'nama')
            ->with('prodi:id,singkatan')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where([
                ['tempat_id', '1'],
                ['prodi_id', '7'],
            ]);
        })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();

        return view('peminjam.ti.buat.create_lab', compact('ruangs', 'barangs', 'peminjams'));
    }

    public function create_luar()
    {
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where([
                ['prodi_id', '7'],
                ['tempat_id', '1']
            ]);
        })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();

        return view('peminjam.ti.buat.create_luar', compact('barangs', 'peminjams'));
    }

    public function store(Request $request)
    {
        $praktik_id = $request->praktik_id;

        if ($praktik_id == '1') {
            return $this->store_lab($request);
        } elseif ($praktik_id == '3') {
            return $this->store_luar($request);
        } else {
            alert()->error('Gagal!', 'Kategori peminjaman tidak ditemukan!');
            return redirect('peminjam/ti/buat');
        }
    }

    public function store_lab(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'ruang_id' => 'required',
                'jumlah' => 'required',
            ], [
                'tanggal.required' => 'Waktu pinjam belum diisi!',
                'jam_awal.required' => 'Jam awal belum diisi!',
                'jam_akhir.required' => 'Jam akhir belum diisi!',
                'ruang_id.required' => 'Ruang lab belum diisi!',
                'jumlah.required' => 'Jumlah komputer belum diisi!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam' => 'required',
                'ruang_id' => 'required',
                'jumlah' => 'required',
            ], [
                'tanggal.required' => 'Waktu pinjam belum diisi!',
                'jam.required' => 'Jam pinjam belum dipilih!',
                'ruang_id.required' => 'Ruang lab belum dipilih!',
                'jumlah.required' => 'Jumlah komputer belum diisi!',
            ]);
        }

        $error_peminjaman = array();

        if ($validator->fails()) {
            $error_peminjaman = $validator->errors()->all();
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman);
        }

        if ($request->jam == 'lainnya') {
            $jam_awal = $request->jam_awal;
            $jam_akhir = $request->jam_akhir;
        } else {
            $jam_awal = substr($request->jam, 0, 5);
            $jam_akhir = substr($request->jam, -5);
        }

        $laboran_id = Ruang::where('id', $request->ruang_id)->value('laboran_id');

        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '1',
            'tanggal_awal' => $request->tanggal,
            'tanggal_akhir' => $request->tanggal,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'ruang_id' => $request->ruang_id,
            'laboran_id' => $laboran_id,
            'bahan' => $request->bahan,
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]);

        $barang = Barang::where('ruang_id', $request->ruang_id)->first();
        DetailPinjam::create([
            'pinjam_id' => $pinjam->id,
            'barang_id' => $barang->id,
            'jumlah' => $request->jumlah,
            'satuan_id' => '6'
        ]);

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/ti/menunggu');
    }

    public function store_luar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'lama' => 'required',
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'keterangan' => 'required',
        ], [
            // 'lama.required' => 'Lama peminjaman belum diisi!',
            'tanggal_awal.required' => 'Tanggal Pinjam belum diisi!',
            'tanggal_akhir.required' => 'Tanggal Kembali belum diisi!',
            'keterangan.required' => 'Keterangan belum diisi!',
        ]);

        $error_peminjaman = array();

        if ($validator->fails()) {
            $error_peminjaman = $validator->errors()->all();
        }

        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('id', $barang_id)
                    ->select(
                        'nama',
                        'ruang_id'
                    )
                    ->with('ruang:id,nama')
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang' => array(
                        'nama' => $barang->ruang->nama
                    ),
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        if (count($error_peminjaman) > 0 || count($error_barang) > 0) {
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_barang', $error_barang)
                ->with('data_items', $data_items);
        }

        $laboran = User::where('prodi_id', '7')->first();

        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '3',
            // 'tanggal_awal' => Carbon::now()->format('Y-m-d'),
            // 'tanggal_akhir' => Carbon::now()->addDays($request->lama)->format('Y-m-d'),
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
            'keterangan' => $request->keterangan,
            'laboran_id' => $laboran->id,
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]);

        foreach ($items as $barang_id => $total) {
            DetailPinjam::create([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $barang_id,
                'jumlah' => $total,
                'satuan_id' => '6'
            ]);
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/ti/menunggu');
    }

    public function check()
    {
        if (auth()->user()->telp == null || auth()->user()->alamat == null) {
            return false;
        } else {
            return true;
        }
    }

    public function jam_kerja()
    {
        $hari = Carbon::now()->format('l');
        $jam = Carbon::now()->format('H:i');

        if ($hari == 'Saturday' || $hari == 'Sunday') {
            return false;
        } else {
            if ($jam >= '08:00' && $jam <= '16:00') {
                return true;
            } else {
                return false;
            }
        }
    }
}
