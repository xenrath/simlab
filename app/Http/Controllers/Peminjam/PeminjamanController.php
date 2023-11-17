<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
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
            ['peminjam_id', auth()->user()->id],
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'kategori',
                'status'
            )
            ->with('praktik:id,nama', 'ruang:id,nama', 'peminjam:id,nama')
            ->orderByDesc('id')
            ->get();

        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();

        return view('peminjam.peminjaman.index', compact('pinjams', 'ruangs'));
    }

    // Tidak ikut resource
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
            // return redirect('peminjam/normal/peminjaman/mandiri?ruang_id=' . $request->ruang_id);
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

        return view('peminjam.peminjaman.create_mandiri', compact('ruang', 'ruangs', 'barangs', 'data'));
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

        return view('peminjam.peminjaman.create_estafet', compact('ruang', 'peminjams', 'ruangs', 'barangs', 'pinjams', 'data'));
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
                $barang = Barang::where('id', $barang_id)->select('nama')->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
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

        return redirect('peminjam/normal/peminjaman');
    }

    public function store_estafet($request)
    {
        if ($request->jam == 'lainnya') {
            $validator = Validator::make($request->all(), [
                'waktu' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'ruang_id' => 'required',
                'kategori' => 'required',
            ], [
                'waktu.required' => 'Waktu Praktik salah!',
                'jam_awal.required' => 'Jam awal belum diisi!',
                'jam_akhir.required' => 'Jam akhir belum diisi!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'ruang_id.required' => 'Ruang lab salah!',
                'kategori.required' => 'Ruang lab salah!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'waktu' => 'required',
                'jam' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'ruang_id' => 'required',
                'kategori' => 'required',
            ], [
                'waktu.required' => 'Waktu Praktik salah!',
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
            'waktu' => $request->waktu,
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

        $waktu = Carbon::now()->addDays($request->waktu)->format('Y-m-d');
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
            'tanggal_awal' => $waktu,
            'tanggal_akhir' => $waktu,
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

        return redirect('peminjam/normal/peminjaman');
    }

    public function show($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

        if ($kategori == 'normal') {
            return $this->show_mandiri($id);
        } else {
            return $this->show_estafet($id);
        }
    }

    public function show_mandiri($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.bahan',
                'pinjams.kategori',
            )
            ->first();
        $detailpinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah',
            )
            ->get();

        return view('peminjam.peminjaman.show_mandiri', compact('pinjam', 'detailpinjams'));
    }

    public function show_estafet($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.tanggal_awal',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.bahan',
                'pinjams.kategori',
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.nama as barang_nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah',
            )
            ->get();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );

        return view('peminjam.peminjaman.show_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok'));
    }

    public function edit($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

        if ($kategori == 'normal') {
            return $this->edit_mandiri($id);
        } else {
            return $this->edit_estafet($id);
        }
    }

    public function edit_mandiri($id, $data = null)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.id as ruang_id',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.bahan',
                'pinjams.kategori',
                'pinjams.status'
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id as id',
                'barangs.nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total',
            )
            ->get();
        // ->pluck('detail_pinjams.id')->toArray();

        // return in_array(999999, $detail_pinjams);

        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->get();
        $barangs = Barang::where('ruang_id', $pinjam->ruang_id)
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruangs.nama as ruang_nama'
            )
            ->orderBy('nama', 'ASC')
            ->get();

        return view('peminjam.peminjaman.edit_mandiri', compact(
            'pinjam',
            'detail_pinjams',
            'ruangs',
            'barangs',
            'data'
        ));
    }

    public function edit_estafet($id, $data = null)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.tanggal_awal',
                'praktiks.nama as praktik_nama',
                'ruangs.id as ruang_id',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.dosen',
                'pinjams.bahan',
                'pinjams.kategori',
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'detail_pinjams.id as detail_pinjam_id',
                'barangs.id as id',
                'barangs.nama',
                'ruangs.nama as ruang_nama',
                'detail_pinjams.jumlah as total',
            )
            ->get();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $ruang = Ruang::where('ruangs.id', $pinjam->ruang_id)
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
        $pinjams = Pinjam::where([
            ['peminjam_id', '!=', auth()->user()->id],
            ['ruang_id', $pinjam->ruang_id],
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

        return view('peminjam.peminjaman.edit_estafet', compact('pinjam', 'detail_pinjams', 'data_kelompok', 'ruang', 'peminjams', 'ruangs', 'barangs', 'pinjams', 'data'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');
        if ($kategori == 'normal') {
            return $this->update_mandiri($request, $id);
        } elseif ($kategori == 'estafet') {
            return $this->update_estafet($request, $id);
        }
    }

    public function update_mandiri($request, $id)
    {
        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('id', $barang_id)->select('nama')->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_mandiri($id, $data);
        }

        if (count($error_barang) > 0) {
            return back()->withInput()
                ->with('data_items', $data_items)
                ->with('error_barang', $error_barang);
        }

        foreach ($items as $barang_id => $total) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $barang_id]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $barang_id]
                ])->update([
                    'jumlah' => $total
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $barang_id,
                    'jumlah' => $total,
                    'satuan_id' => '6'
                ]);
            }
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }

    public function update_estafet($request, $id)
    {
        $items = $request->items;
        $data_items = array();
        $error_barang = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('id', $barang_id)->select('nama')->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        $data['error_barang'] = $error_barang;
        $data['data_items'] = $data_items;

        if (count($error_barang) > 0) {
            return $this->edit_estafet($id, $data);
        }

        if (count($error_barang) > 0) {
            return back()->withInput()
                ->with('data_items', $data_items)
                ->with('error_barang', $error_barang);
        }

        foreach ($items as $barang_id => $total) {
            $detail_pinjam = DetailPinjam::where([
                ['pinjam_id', $id],
                ['barang_id', $barang_id]
            ])->exists();
            if ($detail_pinjam) {
                DetailPinjam::where([
                    ['pinjam_id', $id],
                    ['barang_id', $barang_id]
                ])->update([
                    'jumlah' => $total
                ]);
            } else {
                DetailPinjam::create([
                    'pinjam_id' => $id,
                    'barang_id' => $barang_id,
                    'jumlah' => $total,
                    'satuan_id' => '6'
                ]);
            }
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/normal/peminjaman');
    }

    public function destroy($id)
    {
        $kategori = Pinjam::where('id', $id)->value('kategori');

        if ($kategori == 'normal') {
            return $this->destroy_mandiri($id);
        } else {
            return $this->destroy_estafet($id);
        }
    }

    public function destroy_mandiri($id)
    {
        $pinjam = Pinjam::findOrFail($id);

        $pinjam->detail_pinjams()->delete();
        $pinjam->forceDelete();

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return back();
    }

    public function destroy_estafet($id)
    {
        $pinjam = Pinjam::findOrFail($id);

        $pinjam->detail_pinjams()->delete();
        $pinjam->kelompoks()->delete();
        $pinjam->forceDelete();

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return back();
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

    public function pilih($items)
    {
        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan', 'ruang')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        // return json_encode($barangs);
        return $barangs;
    }

    function toArray($data)
    {
        $array = array();
        foreach ($data as $value) {
            array_push($array, $value);
        }

        return $array;
    }

    public function search(Request $request)
    {
        $ruang_id = $request->keyword_ruang_id;
        $nama = $request->keyword_nama;

        if (!is_null($ruang_id) && !is_null($nama)) {
            $barangs = Barang::where([
                ['barangs.normal', '>', '0'],
                ['barangs.ruang_id', $ruang_id],
                ['barangs.nama', 'like', "%$nama%"]
            ])
                ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                ->select(
                    'barangs.id',
                    'barangs.nama',
                    'ruangs.nama as ruang_nama',
                )
                ->orderBy('nama', 'asc')
                ->get();
        } elseif (!is_null($ruang_id) && is_null($nama)) {
            $barangs = Barang::where([
                ['barangs.normal', '>', '0'],
                ['barangs.ruang_id', $ruang_id]
            ])
                ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                ->select(
                    'barangs.id',
                    'barangs.nama',
                    'ruangs.nama as ruang_nama'
                )
                ->orderBy('nama', 'asc')
                ->get();
        } elseif (is_null($ruang_id) && !is_null($nama)) {
            $barangs = Barang::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '2');
            })->where([
                ['barangs.normal', '>', '0'],
                ['barangs.nama', 'like', "%$nama%"]
            ])
                ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                ->select(
                    'barangs.id',
                    'barangs.nama',
                    'ruangs.nama as ruang_nama'
                )
                ->orderBy('ruangs.kode', 'asc')
                ->orderBy('nama', 'asc')
                ->get();
        } elseif (is_null($ruang_id) && is_null($nama)) {
            $barangs = Barang::where('barangs.normal', '>', '0')->whereHas('ruang', function ($query) {
                $query->where('tempat_id', '2');
            })
                ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
                ->select(
                    'barangs.id',
                    'barangs.nama',
                    'ruangs.nama as ruang_nama'
                )
                ->orderBy('ruangs.kode', 'asc')
                ->orderBy('nama', 'asc')
                ->get();
        }

        return $barangs;
    }
}
