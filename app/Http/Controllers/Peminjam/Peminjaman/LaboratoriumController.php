<?php

namespace App\Http\Controllers\Peminjam\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaboratoriumController extends Controller
{
    public function index()
    {
        $ruangs = Ruang::where([
            ['ruangs.kode', '!=', '01'],
            ['ruangs.kode', '!=', '02']
        ])
            ->join('prodis', 'ruangs.prodi_id', '=', 'prodis.id')
            ->select(
                'ruangs.id',
                'ruangs.nama',
                'prodis.singkatan'
            )
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

        return view('peminjam.peminjaman-new.laboratorium.index', compact('ruangs', 'barangs', 'peminjams'));
    }

    public function store(Request $request)
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

        if (count($error_peminjaman) > 0 || count($error_anggota) > 0 || count($error_barang) > 0) {
            return back()->withInput()
                ->with('data_anggotas', $data_anggotas)
                ->with('data_items', $data_items)
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_anggota', $error_anggota)
                ->with('error_barang', $error_barang);
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
            'ketua' => auth()->user()->id,
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

        return redirect('peminjam/normal/peminjaman-new');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.bahan'
            )
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('id', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('peminjam.peminjaman-new.laboratorium.show', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function edit($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('ruangs', 'pinjams.ruang_id', '=', 'ruangs.id')
            ->join('users', 'ruangs.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'praktiks.nama as praktik_nama',
                'ruangs.nama as ruang_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.bahan'
            )
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        $ketua = User::where('id', $kelompok->ketua)->select('kode', 'nama')->first();
        $anggota = array();
        foreach ($kelompok->anggota as $kode) {
            $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
            array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
        }
        $data_kelompok = array(
            'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
            'anggota' => $anggota
        );
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'barangs.id',
                'barangs.nama as nama',
                'detail_pinjams.jumlah as total'
            )
            ->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })
            ->where('normal', '>', '0')
            ->select('id', 'nama', 'ruang_id')
            ->orderBy('ruang_id', 'ASC')
            ->get();

        return view('peminjam.peminjaman-new.laboratorium.edit', compact('pinjam', 'data_kelompok', 'detail_pinjams', 'barangs'));
    }

    public function update(Request $request, $id)
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

        return redirect('peminjam/normal/peminjaman-new');
    }
}
