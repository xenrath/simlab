<?php

namespace App\Http\Controllers\Peminjam\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Praktik;
use App\Models\Ruang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuatController extends Controller
{
    public function index()
    {
        $praktiks = Praktik::select('id', 'nama')->get();

        return view('peminjam.bidan.buat.index', compact('praktiks'));
    }

    public function show()
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect('peminjam/bidan');
        }

        if (!$this->jam_kerja()) {
            alert()->error('Error!', 'Anda sedang tidak dalam waktu kerja!');
            return back();
        }

        $praktik_id = request()->get('praktik_id');

        if ($praktik_id == '1') {
            return $this->create_lab();
        } elseif ($praktik_id == '2') {
            return $this->create_kelas();
        } elseif ($praktik_id == '3') {
            return $this->create_luar();
        } elseif ($praktik_id == '4') {
            return $this->create_ruang();
        } else {
            alert()->error('Gagal!', 'Kategori praktik tidak ditemukan!');
            return back();
        }
    }

    public function create_lab()
    {
        $praktik = Praktik::where('id', '1')->select('id', 'nama')->first();
        $ruangs = Ruang::where([
            ['tempat_id', '1'],
            ['kode', '!=', '01'],
            ['kode', '!=', '02']
        ])
            ->select('id', 'prodi_id', 'nama')
            ->with('prodi:id,singkatan')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();

        return view('peminjam.bidan.buat.create_lab', compact('praktik', 'ruangs', 'barangs', 'peminjams'));
    }

    public function create_kelas()
    {
        $praktik = Praktik::where('id', '2')->select('id', 'nama')->first();
        $laborans = User::where('role', 'laboran')
            ->whereHas('ruangs', function ($query) {
                $query->where('tempat_id', '1');
            })
            ->select('id', 'nama')
            ->with('ruangs', function ($query) {
                $query->with('prodi');
            })
            ->orderBy('id')
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();

        return view('peminjam.bidan.buat.create_kelas', compact('praktik', 'laborans', 'barangs', 'peminjams'));
    }

    public function create_luar()
    {
        $praktik = Praktik::where('id', '3')->select('id', 'nama')->first();
        $laborans = User::where('role', 'laboran')
            ->whereHas('ruangs', function ($query) {
                $query->where('tempat_id', '1');
            })
            ->select('id', 'nama')
            ->with('ruangs', function ($query) {
                $query->with('prodi');
            })
            ->orderBy('id')
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();

        return view('peminjam.bidan.buat.create_luar', compact('praktik', 'laborans', 'barangs', 'peminjams'));
    }

    public function create_ruang()
    {
        $praktik = Praktik::where('id', '4')->select('id', 'nama')->first();
        $ruangs = Ruang::where([
            ['tempat_id', '1'],
            ['kode', '!=', '01'],
            ['kode', '!=', '02']
        ])
            ->select('id', 'prodi_id', 'nama')
            ->with('prodi:id,singkatan')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();

        return view('peminjam.bidan.buat.create_ruang', compact('praktik', 'ruangs', 'peminjams'));
    }

    public function store(Request $request)
    {
        $praktik_id = $request->praktik_id;

        if ($praktik_id == '1') {
            return $this->store_lab($request);
        } elseif ($praktik_id == '2') {
            return $this->store_kelas($request);
        } elseif ($praktik_id == '3') {
            return $this->store_luar($request);
        } elseif ($praktik_id == '4') {
            return $this->store_ruang($request);
        } else {
            alert()->error('Gagal!', 'Kategori praktik tidak ditemukan!');
            return redirect('peminjam/bidan/buat');
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
                'matakuliah' => 'required',
                'praktik' => 'required',
                'dosen' => 'required',
                'kelas' => 'required',
            ], [
                'tanggal.required' => 'Waktu praktik belum diisi!',
                'jam_awal.required' => 'Jam awal belum diisi!',
                'jam_akhir.required' => 'Jam akhir belum diisi!',
                'ruang_id.required' => 'Ruang lab belum diisi!',
                'matakuliah.required' => 'Mata kuliah belum diisi!',
                'praktik.required' => 'Praktik belum diisi!',
                'dosen.required' => 'Dosen pengampu belum diisi!',
                'kelas.required' => 'Tingkat kelas belum diisi!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam' => 'required',
                'ruang_id' => 'required',
                'matakuliah' => 'required',
                'praktik' => 'required',
                'dosen' => 'required',
                'kelas' => 'required',
            ], [
                'tanggal.required' => 'Waktu praktik belum diisi!',
                'jam.required' => 'Jam praktik belum dipilih!',
                'ruang_id.required' => 'Ruang lab belum dipilih!',
                'matakuliah.required' => 'Mata kuliah belum diisi!',
                'praktik.required' => 'Praktik belum diisi!',
                'dosen.required' => 'Dosen pengampu belum diisi!',
                'kelas.required' => 'Tingkat kelas belum diisi!',
            ]);
        }

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

        } else {
            array_push($error_barang, 'Barang belum ditambahkan!');
        }

        if (count($error_peminjaman) > 0 || count($error_anggota) > 0 || count($error_barang) > 0) {
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_anggota', $error_anggota)
                ->with('data_anggotas', $data_anggotas)
                ->with('error_barang', $error_barang)
                ->with('data_items', $data_items);
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
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'kelas' => $request->kelas,
            'ruang_id' => $request->ruang_id,
            'laboran_id' => $laboran_id,
            'bahan' => $request->bahan,
            'kategori' => 'normal',
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

        return redirect('peminjam/bidan/menunggu');
    }

    public function store_kelas($request)
    {
        if ($request->jam == 'lainnya') {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'matakuliah' => 'required',
                'praktik' => 'required',
                'dosen' => 'required',
                'kelas' => 'required',
                'keterangan' => 'required',
                'laboran_id' => 'required',
            ], [
                'tanggal.required' => 'Waktu praktik belum diisi!',
                'jam_awal.required' => 'Jam awal belum diisi!',
                'jam_akhir.required' => 'Jam akhir belum diisi!',
                'matakuliah.required' => 'Mata kuliah belum diisi!',
                'praktik.required' => 'Praktik belum diisi!',
                'dosen.required' => 'Dosen pengampu belum diisi!',
                'kelas.required' => 'Tingkat kelas belum diisi!',
                'keterangan.required' => 'Ruang kelas belum diisi!',
                'laboran_id.required' => 'Laboran penerima belum dipilih!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam' => 'required',
                'matakuliah' => 'required',
                'praktik' => 'required',
                'dosen' => 'required',
                'kelas' => 'required',
                'keterangan' => 'required',
                'laboran_id' => 'required',
            ], [
                'tanggal.required' => 'Waktu praktik belum diisi!',
                'jam.required' => 'Jam praktik belum dipilih!',
                'matakuliah.required' => 'Mata kuliah belum diisi!',
                'praktik.required' => 'Praktik belum diisi!',
                'dosen.required' => 'Dosen pengampu belum diisi!',
                'kelas.required' => 'Tingkat kelas belum diisi!',
                'keterangan.required' => 'Ruang kelas belum diisi!',
                'laboran_id.required' => 'Laboran penerima belum dipilih!',
            ]);
        }

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

        if (count($error_peminjaman) > 0 || count($error_anggota) > 0 || count($error_barang) > 0) {
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_anggota', $error_anggota)
                ->with('data_anggotas', $data_anggotas)
                ->with('error_barang', $error_barang)
                ->with('data_items', $data_items);
        }

        if ($request->jam == 'lainnya') {
            $jam_awal = $request->jam_awal;
            $jam_akhir = $request->jam_akhir;
        } else {
            $jam_awal = substr($request->jam, 0, 5);
            $jam_akhir = substr($request->jam, -5);
        }

        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '2',
            'tanggal_awal' => $request->tanggal,
            'tanggal_akhir' => $request->tanggal,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'matakuliah' => $request->matakuliah,
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'kelas' => $request->kelas,
            'keterangan' => $request->keterangan,
            'laboran_id' => $request->laboran_id,
            'bahan' => $request->bahan,
            'kategori' => 'normal',
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

        return redirect('peminjam/bidan/menunggu');
    }

    public function store_luar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lama' => 'required',
            'matakuliah' => 'required',
            'praktik' => 'required',
            'dosen' => 'required',
            'kelas' => 'required',
            'keterangan' => 'required',
            'laboran_id' => 'required',
        ], [
            'lama.required' => 'Lama peminjaman belum diisi!',
            'matakuliah.required' => 'Mata kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen pengampu belum diisi!',
            'kelas.required' => 'Tingkat kelas belum diisi!',
            'keterangan.required' => 'Ruang kelas belum diisi!',
            'laboran_id.required' => 'Laboran penerima belum dipilih!',
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

        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '3',
            'tanggal_awal' => Carbon::now()->format('Y-m-d'),
            'tanggal_akhir' => Carbon::now()->addDays($request->lama)->format('Y-m-d'),
            'matakuliah' => $request->matakuliah,
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'kelas' => $request->kelas,
            'keterangan' => $request->keterangan,
            'laboran_id' => $request->laboran_id,
            'bahan' => $request->bahan,
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

        return redirect('peminjam/bidan/menunggu');
    }

    public function store_ruang(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'ruang_id' => 'required',
                'matakuliah' => 'required',
                'praktik' => 'required',
                'dosen' => 'required',
                'kelas' => 'required',
            ], [
                'tanggal.required' => 'Waktu praktik belum diisi!',
                'jam_awal.required' => 'Jam awal belum diisi!',
                'jam_akhir.required' => 'Jam akhir belum diisi!',
                'ruang_id.required' => 'Ruang lab belum diisi!',
                'matakuliah.required' => 'Mata kuliah belum diisi!',
                'praktik.required' => 'Praktik belum diisi!',
                'dosen.required' => 'Dosen pengampu belum diisi!',
                'kelas.required' => 'Tingkat kelas belum diisi!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'tanggal' => 'required',
                'jam' => 'required',
                'ruang_id' => 'required',
                'matakuliah' => 'required',
                'praktik' => 'required',
                'dosen' => 'required',
                'kelas' => 'required',
            ], [
                'tanggal.required' => 'Waktu praktik belum diisi!',
                'jam.required' => 'Jam praktik belum dipilih!',
                'ruang_id.required' => 'Ruang lab belum dipilih!',
                'matakuliah.required' => 'Mata kuliah belum diisi!',
                'praktik.required' => 'Praktik belum diisi!',
                'dosen.required' => 'Dosen pengampu belum diisi!',
                'kelas.required' => 'Tingkat kelas belum diisi!',
            ]);
        }

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

        if (count($error_peminjaman) > 0 || count($error_anggota) > 0) {
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_anggota', $error_anggota)
                ->with('data_anggotas', $data_anggotas);
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
            'praktik_id' => '4',
            'tanggal_awal' => $request->tanggal,
            'tanggal_akhir' => $request->tanggal,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'matakuliah' => $request->matakuliah,
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'ruang_id' => $request->ruang_id,
            'laboran_id' => $laboran_id,
            'kelas' => $request->kelas,
            'kategori' => 'normal',
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

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/bidan/menunggu');
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
