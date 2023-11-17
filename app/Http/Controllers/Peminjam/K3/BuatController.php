<?php

namespace App\Http\Controllers\Peminjam\K3;

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

        return view('peminjam.k3.buat.index', compact('praktiks'));
    }

    public function create(Request $request)
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect('peminjam/k3');
        }

        if (!$this->jam_kerja()) {
            alert()->error('Error!', 'Anda sedang tidak dalam waktu kerja!');
            return back();
        }

        $validator = Validator::make($request->all(), [
            'praktik_id' => 'required',
        ], [
            'praktik_id.required' => 'Kategori praktik harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            alert()->error('Error!', $error[0]);
            return back()->withInput();
        }

        $praktik_id = $request->praktik_id;

        if ($praktik_id == '1') {
            return $this->create_lab();
        } elseif ($praktik_id == '2') {
            return $this->create_kelas();
        } elseif ($praktik_id == '3') {
            return $this->create_luar();
        } else {
            alert()->error('Gagal!', 'Kategori praktik tidak ditemukan!');
            return back();
        }
    }

    public function create_lab($data = null)
    {
        $ruangs = Ruang::where('prodi_id', auth()->user()->subprodi->prodi_id)
            ->select('id', 'prodi_id', 'nama')
            ->orderBy('ruangs.kode', 'ASC')->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->orderBy('ruang_id', 'ASC')
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();

        return view('peminjam.k3.buat.create_lab', compact('ruangs', 'barangs', 'peminjams', 'data'));
    }

    public function create_kelas($data = null)
    {
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
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->orderBy('ruang_id', 'ASC')
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();

        return view('peminjam.k3.buat.create_kelas', compact('laborans', 'barangs', 'peminjams', 'data'));
    }

    public function create_luar($data = null)
    {
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
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->orderBy('ruang_id', 'ASC')
            ->get();
        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->get();

        return view('peminjam.k3.buat.create_luar', compact('laborans', 'barangs', 'peminjams', 'data'));
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
        } else {
            alert()->error('Gagal!', 'Kategori praktik tidak ditemukan!');
            return redirect('peminjam/k3/buat');
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
            'ruang_id' => $request->ruang_id,
            'matakuliah' => $request->matakuliah,
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'kelas' => $request->kelas,
            'bahan' => $request->bahan,
        );

        if (count($error_peminjaman) > 0 || count($error_anggota) > 0 || count($error_barang) > 0) {
            return $this->create_lab($data);
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
            'praktik_id' => '1',
            'tanggal_awal' => $request->tanggal,
            'tanggal_akhir' => $request->tanggal,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'matakuliah' => $request->matakuliah,
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'ruang_id' => $request->ruang_id,
            'kelas' => $request->kelas,
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

        return redirect('peminjam/k3/menunggu');
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
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'kelas' => $request->kelas,
            'keterangan' => $request->keterangan,
            'laboran_id' => $request->laboran_id,
            'bahan' => $request->bahan,
        );

        if (count($error_peminjaman) > 0 || count($error_anggota) > 0 || count($error_barang) > 0) {
            return $this->create_kelas($data);
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

        return redirect('peminjam/k3/menunggu');
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
            'lama' => $request->lama,
            'matakuliah' => $request->matakuliah,
            'praktik' => $request->praktik,
            'dosen' => $request->dosen,
            'kelas' => $request->kelas,
            'keterangan' => $request->keterangan,
            'laboran_id' => $request->laboran_id,
            'bahan' => $request->bahan,
        );

        if (count($error_peminjaman) > 0 || count($error_barang) > 0) {
            return $this->create_luar($data);
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

        return redirect('peminjam/k3/menunggu');
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
