<?php

namespace App\Http\Controllers\Peminjam\LabTerpadu;

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
        return view('peminjam.labterpadu.buat.index', compact('praktiks'));
    }

    public function create()
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect('peminjam/labterpadu');
        }
        // 
        if (!$this->jam_kerja()) {
            alert()->error('Error!', 'Anda sedang tidak dalam waktu kerja!');
            return back();
        }
        // 
        $praktik_id = request()->get('praktik_id');
        if ($praktik_id == '1') {
            return redirect('peminjam/labterpadu/buat/create-praktik-laboratorium');
        } elseif ($praktik_id == '2') {
            return redirect('peminjam/labterpadu/buat/create-praktik-kelas');
        } elseif ($praktik_id == '3') {
            return redirect('peminjam/labterpadu/buat/create-praktik-luar');
        } elseif ($praktik_id == '4') {
            return redirect('peminjam/labterpadu/buat/create-praktik-ruang');
        } else {
            alert()->error('Gagal!', 'Kategori praktik tidak ditemukan!');
            return back();
        }
    }

    public function create_praktik_laboratorium()
    {
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
        // 
        return view('peminjam.labterpadu.buat.create_lab', compact('ruangs', 'barangs', 'peminjams'));
    }

    public function create_praktik_kelas()
    {
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
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', auth()->user()->subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();
        // 
        return view('peminjam.labterpadu.buat.create_kelas', compact('laborans', 'barangs', 'peminjams'));
    }

    public function create_praktik_luar()
    {
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
        // 
        return view('peminjam.labterpadu.buat.create_luar', compact('laborans', 'barangs'));
    }

    public function create_praktik_ruang()
    {
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
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', auth()->user()->subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->get();
        // 
        return view('peminjam.labterpadu.buat.create_ruang', compact('ruangs', 'peminjams'));
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
            return redirect('peminjam/labterpadu/buat');
        }
    }

    public function store_praktik_laboratorium(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator_jam = 'required';
        } else {
            $validator_jam = 'nullable';
        }
        // 
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
        //
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
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal membuat Peminjaman!');
            return back()->withInput()->withErrors($validator->errors())->with('old_barangs', $old_barangs);
        }
        // 
        if ($request->jam == 'lainnya') {
            $jam_awal = $request->jam_awal;
            $jam_akhir = $request->jam_akhir;
        } else {
            $jam_awal = substr($request->jam, 0, 5);
            $jam_akhir = substr($request->jam, -5);
        }
        // 
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
        // 
        if ($request->anggotas) {
            $anggota = array();
            foreach ($request->anggotas as $value) {
                $kode = User::where([
                    ['role', 'peminjam'],
                    ['id', $value],
                ])->value('kode');
                array_push($anggota, $kode);
            }
            // 
            Kelompok::create([
                'pinjam_id' => $pinjam->id,
                'ketua' => auth()->user()->kode,
                'anggota' => $anggota,
            ]);
        }
        // 
        foreach ($request->barangs as $key => $value) {
            DetailPinjam::create([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $value['id'],
                'jumlah' => $value['jumlah'],
                'satuan_id' => '6'
            ]);
        }
        // 
        alert()->success('Success', 'Berhasil membuat Peminjaman');
        return redirect('peminjam/labterpadu/menunggu');
    }

    public function store_praktik_kelas(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator_jam = 'required';
        } else {
            $validator_jam = 'nullable';
        }
        // 
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
            'tanggal.required' => 'Waktu praktik belum diisi!',
            'jam.required' => 'Jam Praktik belum dipilih!',
            'jam_awal.required' => 'Jam awal belum diisi!',
            'jam_akhir.required' => 'Jam akhir belum diisi!',
            'matakuliah.required' => 'Mata kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen pengampu belum diisi!',
            'kelas.required' => 'Tingkat kelas belum diisi!',
            'keterangan.required' => 'Ruang Kelas belum diisi!',
            'laboran_id.required' => 'Laboran Penerima belum dipilih!',
            'barangs.required' => 'Barang belum ditambahkan!',
        ]);
        //
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
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal membuat Peminjaman!');
            return back()->withInput()->withErrors($validator->errors())->with('old_barangs', $old_barangs);
        }
        // 
        if ($request->jam == 'lainnya') {
            $jam_awal = $request->jam_awal;
            $jam_akhir = $request->jam_akhir;
        } else {
            $jam_awal = substr($request->jam, 0, 5);
            $jam_akhir = substr($request->jam, -5);
        }
        // 
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
        // 
        if ($request->anggotas) {
            $anggota = array();
            foreach ($request->anggotas as $value) {
                $kode = User::where([
                    ['role', 'peminjam'],
                    ['id', $value],
                ])->value('kode');
                array_push($anggota, $kode);
            }
            // 
            Kelompok::create([
                'pinjam_id' => $pinjam->id,
                'ketua' => auth()->user()->kode,
                'anggota' => $anggota,
            ]);
        }
        // 
        foreach ($request->barangs as $key => $value) {
            DetailPinjam::create([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $value['id'],
                'jumlah' => $value['jumlah'],
                'satuan_id' => '6'
            ]);
        }
        // 
        alert()->success('Success', 'Berhasil membuat Peminjaman');
        return redirect('peminjam/labterpadu/menunggu');
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
        ], [
            'tanggal_awal.required' => 'Tanggal Mulai belum diisi!',
            'tanggal_akhir.required' => 'Tanggal Selesai belum diisi!',
            'matakuliah.required' => 'Mata kuliah belum diisi!',
            'praktik.required' => 'Praktik belum diisi!',
            'dosen.required' => 'Dosen pengampu belum diisi!',
            'kelas.required' => 'Tingkat kelas belum diisi!',
            'keterangan.required' => 'Ruang Kelas belum diisi!',
            'laboran_id.required' => 'Laboran Penerima belum dipilih!',
            'barangs.required' => 'Barang belum ditambahkan!',
        ]);
        //
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
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal membuat Peminjaman!');
            return back()->withInput()->withErrors($validator->errors())->with('old_barangs', $old_barangs);
        }
        // 
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
        // 
        foreach ($request->barangs as $key => $value) {
            DetailPinjam::create([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $value['id'],
                'jumlah' => $value['jumlah'],
                'satuan_id' => '6'
            ]);
        }
        // 
        alert()->success('Success', 'Berhasil membuat Peminjaman');
        return redirect('peminjam/labterpadu/menunggu');
    }

    public function store_praktik_ruang(Request $request)
    {
        if ($request->jam == 'lainnya') {
            $validator_jam = 'required';
        } else {
            $validator_jam = 'nullable';
        }
        // 
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
        ]);
        //
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal membuat Peminjaman!');
            return back()->withInput()->withErrors($validator->errors());
        }
        // 
        if ($request->jam == 'lainnya') {
            $jam_awal = $request->jam_awal;
            $jam_akhir = $request->jam_akhir;
        } else {
            $jam_awal = substr($request->jam, 0, 5);
            $jam_akhir = substr($request->jam, -5);
        }
        // 
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
            'kelas' => $request->kelas,
            'ruang_id' => $request->ruang_id,
            'laboran_id' => $laboran_id,
            'bahan' => $request->bahan,
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]);
        // 
        if ($request->anggotas) {
            $anggota = array();
            foreach ($request->anggotas as $value) {
                $kode = User::where([
                    ['role', 'peminjam'],
                    ['id', $value],
                ])->value('kode');
                array_push($anggota, $kode);
            }
            // 
            Kelompok::create([
                'pinjam_id' => $pinjam->id,
                'ketua' => auth()->user()->kode,
                'anggota' => $anggota,
            ]);
        }
        // 
        alert()->success('Success', 'Berhasil membuat Peminjaman');
        return redirect('peminjam/labterpadu/menunggu');
    }

    public function check()
    {
        if (auth()->user()->telp) {
            return true;
        } else {
            return false;
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
