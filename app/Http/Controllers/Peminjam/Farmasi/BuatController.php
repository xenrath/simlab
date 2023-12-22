<?php

namespace App\Http\Controllers\Peminjam\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
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
        $ruangs = Ruang::where([
            ['id', '!=', '2'],
            ['prodi_id', auth()->user()->subprodi->prodi_id],
        ])
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();

        return view('peminjam.farmasi.buat.index', compact('ruangs'));
    }

    public function create(Request $request)
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect("peminjam");
        }

        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'ruang_id' => 'required',
        ], [
            'kategori.required' => 'Kategori harus dipilih!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            alert()->error('Error!', $error[0]);
            return back()->withInput();
        }

        if ($request->kategori == 'estafet') {
            return $this->create_estafet($request->ruang_id);
        } elseif ($request->kategori == 'mandiri') {
            return $this->create_mandiri($request->ruang_id);
        }
    }

    public function create_mandiri($ruang_id, $data = null)
    {
        $ruang = Ruang::where('ruangs.id', $ruang_id)
            ->join('users', 'ruangs.laboran_id', 'users.id')
            ->select(
                'ruangs.id',
                'ruangs.nama',
                'users.nama as laboran_nama'
            )->first();
        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();
        $barangs = Barang::where('ruang_id', $ruang->id)
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama'
            )
            ->orderBy('nama', 'ASC')
            ->get();

        return view('peminjam.farmasi.buat.create_mandiri', compact('ruang', 'ruangs', 'barangs', 'data'));
    }

    public function create_estafet($ruang_id, $data = null)
    {
        $ruang = Ruang::where('ruangs.id', $ruang_id)
            ->join('users', 'ruangs.laboran_id', 'users.id')
            ->select(
                'ruangs.id',
                'ruangs.nama',
                'users.nama as laboran_nama'
            )->first();
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', auth()->user()->subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();
        $ruangs = Ruang::where([
            ['prodi_id', auth()->user()->subprodi->prodi_id],
            ['kode', '!=', '02']
        ])
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();
        $barangs = Barang::where('ruang_id', $ruang->id)
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'barangs.normal',
                'ruangs.nama as ruang_nama'
            )
            ->orderBy('nama', 'ASC')
            ->get();
        $pinjams = Pinjam::where([
            ['peminjam_id', '!=', auth()->user()->id],
            ['ruang_id', $ruang_id],
            ['kategori', 'estafet'],
            ['status', '!=', 'selesai'],
            ['status', '!=', 'tagihan'],
        ])
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'users.kode as peminjam_kode',
                'users.nama as peminjam_nama',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir'
            )
            ->get();

        return view('peminjam.farmasi.buat.create_estafet', compact('ruang', 'peminjams', 'ruangs', 'barangs', 'pinjams', 'data'));
    }

    public function store(Request $request)
    {
        if ($request->kategori == 'estafet') {
            return $this->store_estafet($request);
        } elseif ($request->kategori == 'mandiri') {
            return $this->store_mandiri($request);
        }
    }

    public function store_mandiri($request)
    {
        $validator = Validator::make($request->all(), [
            'matakuliah' => 'required',
            'dosen' => 'required',
        ], [
            'matakuliah.required' => 'Mata kuliah harus diisi!',
            'dosen.required' => 'Dosen pengampu harus diisi!',
        ]);

        $data = array();
        $error_peminjaman = array();

        if ($validator->fails()) {
            $error_peminjaman = $validator->errors()->all();
        }

        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('barangs.id', $barang_id)
                    ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                    ->select(
                        'barangs.nama',
                        'ruangs.nama as ruang_nama'
                    )
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang_nama' => $barang->ruang_nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_peminjaman'] = $error_peminjaman;
        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;
        $data['data_old'] = array(
            'matakuliah' => $request->matakuliah,
            'dosen' => $request->dosen,
            'bahan' => $request->bahan,
        );

        if (count($error_peminjaman) > 0 || count($error_barang) > 0) {
            return $this->create_mandiri($request->ruang_id, $data);
        }

        $tanggal_awal = Carbon::now()->format('Y-m-d');
        $tanggal_akhir = Carbon::now()->addDays(7)->format('Y-m-d');
        $laboran_id = Ruang::where('id', $request->ruang_id)->value('laboran_id');

        $pinjam = Pinjam::create(array_merge($request->all(), [
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '1',
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'laboran_id' => $laboran_id,
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]));

        foreach ($items as $barang_id => $total) {
            DetailPinjam::create([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $barang_id,
                'jumlah' => $total,
                'satuan_id' => '6'
            ]);
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/farmasi/menunggu');
    }

    public function store_estafet($request)
    {
        if ($request->jam == 'lainnya') {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'ruang_id' => 'required',
                'kategori' => 'required',
            ], [
                'tanggal.required' => 'Waktu Praktik salah!',
                'jam_awal.required' => 'Jam awal belum diisi!',
                'jam_akhir.required' => 'Jam akhir belum diisi!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'ruang_id.required' => 'Ruang lab salah!',
                'kategori.required' => 'Ruang lab salah!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'ruang_id' => 'required',
                'kategori' => 'required',
            ], [
                'tanggal.required' => 'Tanggal praktik harus diisi!',
                'jam.required' => 'Jam praktik belum dipilih!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'ruang_id.required' => 'Ruang lab salah!',
                'kategori.required' => 'Ruang lab salah!',
            ]);
        }

        $data = array();
        $error_peminjaman = array();

        if ($validator->fails()) {
            $error_peminjaman = $validator->errors()->all();
        }

        $anggotas = $request->anggotas;
        $data_anggotas = array();
        $error_anggota = array();

        if (!is_null($anggotas)) {
            foreach ($anggotas as $id => $kode) {
                $user = User::where('id', $id)->select('nama')->first();
                array_push($data_anggotas, array(
                    'id' => $id,
                    'kode' => $kode,
                    'nama' => $user->nama
                ));
            }
        } else {
            array_push($error_anggota, 'Anggota belum ditambahkan!');
        }

        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('barangs.id', $barang_id)
                    ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                    ->select(
                        'barangs.nama',
                        'ruangs.nama as ruang_nama'
                    )
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang_nama' => $barang->ruang_nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_peminjaman'] = $error_peminjaman;
        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;
        $data['error_anggota'] = $error_anggota;
        $data['data_anggotas'] = $data_anggotas;
        $data['data_old'] = array(
            'tanggal' => $request->tanggal,
            'jam' => $request->jam,
            'jam_awal' => $request->jam_awal,
            'jam_akhir' => $request->jam_akhir,
            'matakuliah' => $request->matakuliah,
            'dosen' => $request->dosen,
            'bahan' => $request->bahan,
        );

        if (count($error_peminjaman) > 0 || count($error_barang) > 0 || count($error_anggota) > 0) {
            return $this->create_estafet($request->ruang_id, $data);
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
            'matakuliah' => $request->matakuliah,
            'dosen' => $request->dosen,
            'bahan' => $request->bahan,
            'ruang_id' => $request->ruang_id,
            'laboran_id' => $laboran_id,
            'kategori' => 'estafet',
            'status' => 'menunggu'
        ]);

        $anggota = array();
        foreach ($request->anggotas as $value) {
            array_push($anggota, $value);
        }

        Kelompok::create(array_merge([
            'pinjam_id' => $pinjam->id,
            'ketua' => auth()->user()->kode,
            'anggota' => $anggota,
        ]));

        foreach ($items as $barang_id => $total) {
            DetailPinjam::create([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $barang_id,
                'jumlah' => $total,
                'satuan_id' => '6'
            ]);
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/farmasi/menunggu');
    }

    public function check()
    {
        if (auth()->user()->telp == null || auth()->user()->alamat == null) {
            return false;
        } else {
            return true;
        }
    }
}
