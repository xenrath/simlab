<?php

namespace App\Http\Controllers\Peminjam\Perawat;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\PinjamDetailBahan;
use App\Models\Praktik;
use App\Models\Ruang;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BuatController extends Controller
{
    public function index()
    {
        $praktiks = Praktik::select('id', 'nama')->get();
        return view('peminjam.perawat.buat.index', compact('praktiks'));
    }

    public function create()
    {
        $cek = $this->cek();
        if (!$cek['status']) {
            return back()->with('error', $cek['message']);
        }

        $praktik_id = request()->input('praktik_id');
        switch ($praktik_id) {
            case '1':
                return redirect('peminjam/perawat/buat/praktik-laboratorium');
            case '2':
                return redirect('peminjam/perawat/buat/praktik-kelas');
            case '3':
                return redirect('peminjam/perawat/buat/praktik-luar');
            case '4':
                return redirect('peminjam/perawat/buat/praktik-ruang');
            default:
                alert()->error('Gagal!', 'Kategori praktik belum dipilih!');
                return back();
        }
    }

    public function create_praktik_laboratorium()
    {
        $cek = $this->cek();
        if (!$cek['status']) {
            return redirect('peminjam/perawat/buat')->with('error', $cek['message']);
        }

        $ruangs = Ruang::where([
            ['tempat_id', '1'],
            ['is_praktik', true]
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
        $nim = Str::substr(auth()->user()->kode, 0, 5);
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            ['kode', 'like', $nim . '%'],
        ])
            ->select('id', 'kode', 'nama')
            ->orderBy('kode')
            ->take(10)
            ->get();

        return view('peminjam.perawat.buat.create_lab', compact('ruangs', 'peminjams', 'barangs'));
    }

    public function create_praktik_kelas()
    {
        $cek = $this->cek();

        if (!$cek['status']) {
            return redirect('peminjam/perawat/buat')->with('error', $cek['message']);
        }

        $laborans = User::where('role', 'laboran')
            ->whereHas('ruangs', function ($query) {
                $query->where('tempat_id', '1');
            })
            ->select('id', 'nama')
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
        $nim = Str::substr(auth()->user()->kode, 0, 5);
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            ['kode', 'like', $nim . '%'],
        ])
            ->select('id', 'kode', 'nama')
            ->orderBy('kode')
            ->take(10)
            ->get();

        return view('peminjam.perawat.buat.create_kelas', compact('laborans', 'barangs', 'peminjams'));
    }

    public function create_praktik_luar()
    {
        $cek = $this->cek();

        if (!$cek['status']) {
            return redirect('peminjam/perawat/buat')->with('error', $cek['message']);
        }

        $laborans = User::where('role', 'laboran')
            ->whereHas('ruangs', fn($query) => $query->where('tempat_id', 1))
            ->select('id', 'nama')
            ->orderBy('id')
            ->get();

        $barangs = Barang::whereHas('ruang', fn($query) => $query->where('tempat_id', 1))
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('peminjam.perawat.buat.create_luar', compact('laborans', 'barangs'));
    }

    public function create_praktik_ruang()
    {
        $cek = $this->cek();
        if (!$cek['status']) {
            return redirect('peminjam/perawat/buat')->with('error', $cek['message']);
        }

        $ruangs = Ruang::where([
            ['tempat_id', '1'],
            ['is_praktik', true]
        ])
            ->select('id', 'prodi_id', 'nama')
            ->with('prodi:id,singkatan')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->get();

        $subprodi_id = auth()->user()->subprodi_id;
        $nim = Str::substr(auth()->user()->kode, 0, 5);
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            ['kode', 'like', $nim . '%'],
        ])
            ->select('id', 'kode', 'nama')
            ->orderBy('kode')
            ->take(10)
            ->get();

        return view('peminjam.perawat.buat.create_ruang', compact('ruangs', 'peminjams'));
    }

    public function store_praktik_laboratorium(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator_jam = 'required';
        } else {
            $validator_jam = 'nullable';
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'jam_awal' => $validator_jam,
            'jam_akhir' => $validator_jam,
            'ruang_id' => 'required',
            'matakuliah' => 'required',
            'praktik' => 'required',
            'dosen' => 'required',
            'kelas' => 'required',
            'barangs' => 'required',
        ], [
            'tanggal.required' => 'Waktu praktik belum diisi!',
            'jam.required' => 'Jam Praktik belum dipilih!',
            'jam_awal.required' => 'Jam awal belum diisi!',
            'jam_akhir.required' => 'Jam akhir belum diisi!',
            'ruang_id.required' => 'Ruang lab belum diisi!',
            'matakuliah.required' => 'Mata kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen pengampu belum diisi!',
            'kelas.required' => 'Tingkat kelas belum diisi!',
            'barangs.required' => 'Barang belum ditambahkan!',
        ]);

        $old_barangs = array();
        if ($request->barangs) {
            foreach ($request->barangs as $value) {
                $barang = Barang::where('id', $value['id'])
                    ->select(
                        'nama',
                        'ruang_id',
                    )
                    ->with('ruang:id,nama')
                    ->first();
                array_push($old_barangs, array(
                    'id' => $value['id'],
                    'nama' => $barang->nama,
                    'ruang' => array('nama' => $barang->ruang->nama),
                    'jumlah' => $value['jumlah'],
                ));
            }
        }

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('old_barangs', $old_barangs)
                ->with('error', 'Gagal membuat Peminjaman!');
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
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]);

        if (!empty($request->anggotas)) {
            $anggota_kode = User::where('role', 'peminjam')
                ->whereIn('id', $request->anggotas)
                ->pluck('kode')
                ->toArray();
            Kelompok::create([
                'pinjam_id' => $pinjam->id,
                'ketua' => auth()->user()->kode,
                'anggota' => $anggota_kode,
            ]);
        }

        foreach ($request->barangs as $value) {
            DetailPinjam::create([
                'pinjam_id'  => $pinjam->id,
                'barang_id'  => $value['id'],
                'jumlah'     => $value['jumlah'],
                'satuan_id'  => 6, // ga perlu string kalau angka
            ]);
        }

        return redirect('peminjam/perawat/menunggu')->with('success', 'Berhasil membuat Peminjaman');
    }

    public function store_praktik_kelas(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator_jam = 'required';
        } else {
            $validator_jam = 'nullable';
        }

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'jam_awal' => $validator_jam,
            'jam_akhir' => $validator_jam,
            'matakuliah' => 'required',
            'praktik' => 'required',
            'dosen' => 'required',
            'kelas' => 'required',
            'keterangan' => 'required',
            'laboran_id' => 'required',
            'barangs' => 'required',
        ], [
            'tanggal.required' => 'Waktu Praktik belum diisi!',
            'jam.required' => 'Jam Praktik belum dipilih!',
            'jam_awal.required' => 'Jam Awal belum diisi!',
            'jam_akhir.required' => 'Jam Akhir belum diisi!',
            'matakuliah.required' => 'Mata Kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen Pengampu belum diisi!',
            'kelas.required' => 'Tingkat Kelas belum diisi!',
            'keterangan.required' => 'Ruang Kelas belum diisi!',
            'laboran_id.required' => 'Laboran Penerima belum dipilih!',
            'barangs.required' => 'Barang belum ditambahkan!',
        ]);

        $old_barangs = array();
        if ($request->barangs) {
            foreach ($request->barangs as $value) {
                $barang = Barang::where('id', $value['id'])
                    ->select(
                        'nama',
                        'ruang_id',
                    )
                    ->with('ruang:id,nama')
                    ->first();
                array_push($old_barangs, array(
                    'id' => $value['id'],
                    'nama' => $barang->nama,
                    'ruang' => array('nama' => $barang->ruang->nama),
                    'jumlah' => $value['jumlah'],
                ));
            }
        }

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('old_barangs', $old_barangs)
                ->with('error', 'Gagal membuat Peminjaman!');
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
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]);

        if (!empty($request->anggotas)) {
            $anggota_kode = User::where('role', 'peminjam')
                ->whereIn('id', $request->anggotas)
                ->pluck('kode')
                ->toArray();

            Kelompok::create([
                'pinjam_id' => $pinjam->id,
                'ketua' => auth()->user()->kode,
                'anggota' => $anggota_kode,
            ]);
        }

        foreach ($request->barangs as $value) {
            DetailPinjam::create([
                'pinjam_id'  => $pinjam->id,
                'barang_id'  => $value['id'],
                'jumlah'     => $value['jumlah'],
                'satuan_id'  => 6,
            ]);
        }

        return redirect('peminjam/perawat/menunggu')->with('success', 'Berhasil membuat Peminjaman');
    }

    public function store_praktik_luar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
            'matakuliah' => 'required',
            'praktik' => 'required',
            'dosen' => 'required',
            'kelas' => 'required',
            'keterangan' => 'required',
            'laboran_id' => 'required',
            'barangs' => 'required',
            'bahans.*.jumlah' => 'required|numeric|gt:0',
        ], [
            'tanggal_awal.required' => 'Tanggal Mulai belum diisi!',
            'tanggal_akhir.required' => 'Tanggal Selesai belum diisi!',
            'matakuliah.required' => 'Mata Kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen Pengampu belum diisi!',
            'kelas.required' => 'Tingkat Kelas belum diisi!',
            'keterangan.required' => 'Ruang Kelas belum diisi!',
            'laboran_id.required' => 'Laboran Penerima belum dipilih!',
            'barangs.required' => 'Barang belum ditambahkan!',
            'bahans.*.jumlah.required' => 'Jumlah belum diisi!',
            'bahans.*.numeric.required' => 'Jumlah harus numeric!',
            'bahans.*.gt.required' => 'Jumlah tidak boleh 0!',
        ]);

        $old_barangs = array();
        if ($request->barangs) {
            foreach ($request->barangs as $key => $value) {
                $barang = Barang::where('id', $value['id'])
                    ->select(
                        'nama',
                        'ruang_id',
                    )
                    ->with('ruang:id,nama')
                    ->first();
                array_push($old_barangs, array(
                    'id' => $value['id'],
                    'nama' => $barang->nama,
                    'ruang' => array('nama' => $barang->ruang->nama),
                    'jumlah' => $value['jumlah'],
                ));
            }
        }

        $old_bahans = array();
        if ($request->bahans) {
            foreach ($request->bahans as $value) {
                array_push($old_bahans, array(
                    'id' => $value['bahan_id'],
                    'nama' => $value['bahan_nama'],
                    'prodi' => array('id' => $value['prodi_id'], 'nama' => $value['prodi_nama']),
                    'jumlah' => $value['jumlah'],
                    'satuan_pinjam' => $value['satuan_pinjam'],
                ));
            }
        }

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('old_barangs', $old_barangs)
                ->with('old_bahans', $old_bahans)
                ->with('error', 'Gagal membuat Peminjaman!');
        }

        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '3',
            'tanggal_awal' => $request->tanggal_awal,
            'tanggal_akhir' => $request->tanggal_akhir,
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

        foreach ($request->barangs as $value) {
            DetailPinjam::create([
                'pinjam_id'  => $pinjam->id,
                'barang_id'  => $value['id'],
                'jumlah'     => $value['jumlah'],
                'satuan_id'  => 6,
            ]);
        }

        foreach ($request->input('bahans', []) as $value) {
            PinjamDetailBahan::create([
                'pinjam_id'   => $pinjam->id,
                'bahan_id'    => $value['bahan_id'],
                'bahan_nama'  => $value['bahan_nama'],
                'prodi_id'    => $value['prodi_id'],
                'prodi_nama'  => $value['prodi_nama'],
                'jumlah'      => $value['jumlah'],
                'satuan'      => $value['satuan_pinjam'],
            ]);
        }

        return redirect('peminjam/perawat/menunggu')->with('success', 'Berhasil membuat Peminjaman');
    }

    public function store_praktik_ruang(Request $request)
    {
        $is_custom_jam = $request->jam === 'lainnya';

        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'jam_awal' => $is_custom_jam ? 'required' : 'nullable',
            'jam_akhir' => $is_custom_jam ? 'required' : 'nullable',
            'ruang_id' => 'required',
            'matakuliah' => 'required',
            'praktik' => 'required',
            'dosen' => 'required',
            'kelas' => 'required',
        ], [
            'tanggal.required' => 'Waktu Praktik belum diisi!',
            'jam.required' => 'Jam Praktik belum dipilih!',
            'jam_awal.required' => 'Jam Awal belum diisi!',
            'jam_akhir.required' => 'Jam Akhir belum diisi!',
            'ruang_id.required' => 'Ruang Lab belum diisi!',
            'matakuliah.required' => 'Mata Kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen Pengampu belum diisi!',
            'kelas.required' => 'Tingkat Kelas belum diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal membuat Peminjaman!');
        }

        $jam_awal = $is_custom_jam ? $request->jam_awal : substr($request->jam, 0, 5);
        $jam_akhir = $is_custom_jam ? $request->jam_akhir : substr($request->jam, -5);

        $laboran_id = Ruang::where('id', $request->ruang_id)->value('laboran_id');
        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->id(),
            'praktik_id' => 4,
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
            'kategori' => 'normal',
            'status' => 'menunggu',
        ]);

        if (!empty($request->anggotas)) {
            $anggota_kode = User::where('role', 'peminjam')
                ->whereIn('id', $request->anggotas)
                ->pluck('kode')
                ->toArray();
            Kelompok::create([
                'pinjam_id' => $pinjam->id,
                'ketua' => auth()->user()->kode,
                'anggota' => $anggota_kode,
            ]);
        }

        return redirect('peminjam/perawat/menunggu')->with('success', 'Berhasil membuat Peminjaman');
    }

    public function cek()
    {
        $user = auth()->user();

        // Cek apakah nomor telepon tersedia
        if (!$user->telp) {
            return ['status' => false, 'message' => 'Lengkapi data diri anda terlebih dahulu!'];
        }

        // Cek hari dan jam
        $hari = Carbon::now()->format('l');
        $jam = Carbon::now()->format('H:i');

        if ($hari === 'Saturday' || $hari === 'Sunday') {
            return ['status' => false, 'message' => 'Hari ini di luar jam kerja!'];
        }

        if ($jam < '08:00' || $jam > '16:00') {
            return ['status' => false, 'message' => 'Anda sedang tidak dalam waktu kerja!'];
        }

        // Semua syarat terpenuhi
        return ['status' => true];
    }
}
