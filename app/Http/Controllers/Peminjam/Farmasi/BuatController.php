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
            ['is_praktik', true],
            ['prodi_id', '4'],
        ])
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();

        return view('peminjam.farmasi.buat.index', compact('ruangs'));
    }

    public function create(Request $request)
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect('peminjam/farmasi');
        }

        $validator = Validator::make($request->all(), [
            'kategori' => 'required',
            'ruang_id' => 'required',
        ], [
            'kategori.required' => 'Kategori Praktik harus dipilih!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error!', 'Gagal membuat Peminjaman!');
            return back()->withInput()->withErrors($validator->errors());
        }

        if ($request->kategori == 'estafet') {
            return redirect('peminjam/farmasi/buat/create-estafet/' . $request->ruang_id);
        } elseif ($request->kategori == 'mandiri') {
            return redirect('peminjam/farmasi/buat/create-mandiri/' . $request->ruang_id);
        }
    }

    public function create_estafet($id)
    {
        $ruang = Ruang::where('id', $id)
            ->select(
                'id',
                'nama',
                'laboran_id'
            )
            ->with('laboran:id,nama')
            ->first();
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', auth()->user()->subprodi_id],
        ])
            ->select('id', 'kode', 'nama')
            ->take(10)
            ->orderBy('nama')
            ->get();
        $ruangs = Ruang::where([
            ['prodi_id', auth()->user()->subprodi->prodi_id],
            ['kode', '!=', '02']
        ])
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();
        $barangs = Barang::where('ruang_id', $ruang->id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();
        $estafets = Pinjam::where([
            ['peminjam_id', '!=', auth()->user()->id],
            ['ruang_id', $id],
            ['kategori', 'estafet'],
            ['status', '!=', 'selesai'],
            ['status', '!=', 'tagihan'],
        ])
            ->select(
                'id',
                'peminjam_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir'
            )
            ->with('peminjam:id,kode,nama')
            ->get();
        // 
        return view('peminjam.farmasi.buat.create_estafet', compact(
            'ruang',
            'peminjams',
            'ruangs',
            'barangs',
            'estafets'
        ));
    }

    public function create_mandiri($id)
    {
        $ruang = Ruang::where('id', $id)
            ->select(
                'id',
                'nama',
                'laboran_id'
            )
            ->with('laboran:id,nama')
            ->first();
        $ruangs = Ruang::where([
            ['prodi_id', auth()->user()->subprodi->prodi_id],
            ['kode', '!=', '02']
        ])
            ->orderBy('nama', 'ASC')
            ->select('id', 'nama')
            ->orderBy('nama')
            ->get();
        $barangs = Barang::where('ruang_id', $ruang->id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('peminjam.farmasi.buat.create_mandiri', compact('ruang', 'ruangs', 'barangs'));
    }

    public function store_estafet(Request $request, $id)
    {
        $validator_jam = $request->jam == 'lainnya' ? 'required' : 'nullable';
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'jam_awal' => $validator_jam,
            'jam_akhir' => $validator_jam,
            'matakuliah' => 'required',
            'dosen' => 'required',
            'anggotas' => 'required',
            'barangs' => 'required',
        ], [
            'tanggal.required' => 'Waktu Praktik harus dipilih!',
            'jam.required' => 'Jam Praktik belum diisi!',
            'jam_awal.required' => 'Jam awal belum diisi!',
            'jam_akhir.required' => 'Jam akhir belum diisi!',
            'matakuliah.required' => 'Mata Kuliah harus diisi!',
            'dosen.required' => 'Dosen Pengampu harus diisi!',
            'anggotas.required' => 'Anggota belum ditambahkan!',
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
        $laboran_id = Ruang::where('id', $id)->value('laboran_id');
        // 
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
            'ruang_id' => $id,
            'laboran_id' => $laboran_id,
            'kategori' => 'estafet',
            'status' => 'menunggu'
        ]);
        // 
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
        return redirect('peminjam/farmasi/menunggu');
    }

    public function store_mandiri(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'matakuliah' => 'required',
            'dosen' => 'required',
            'barangs' => 'required',
        ], [
            'matakuliah.required' => 'Mata Kuliah harus diisi!',
            'dosen.required' => 'Dosen Pengampu harus diisi!',
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
        $tanggal_awal = Carbon::now()->format('Y-m-d');
        $tanggal_akhir = Carbon::now()->addDays(7)->format('Y-m-d');
        $laboran_id = Ruang::where('id', $id)->value('laboran_id');
        // 
        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => '1',
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'matakuliah' => $request->matakuliah,
            'dosen' => $request->dosen,
            'bahan' => $request->bahan,
            'ruang_id' => $id,
            'laboran_id' => $laboran_id,
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
        return redirect('peminjam/farmasi/menunggu');
    }

    public function store_estafet1(Request $request, $id)
    {
        $validator_jam = $request->jam == 'lainnya' ? 'required' : 'nullable';
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
            'jam' => 'required',
            'jam_awal' => $validator_jam,
            'jam_akhir' => $validator_jam,
            'matakuliah' => 'required',
            'dosen' => 'required',
        ], [
            'tanggal.required' => 'Waktu Praktik harus dipilih!',
            'jam.required' => 'Jam Praktik belum diisi!',
            'jam_awal.required' => 'Jam awal belum diisi!',
            'jam_akhir.required' => 'Jam akhir belum diisi!',
            'matakuliah.required' => 'Mata Kuliah harus diisi!',
            'dosen.required' => 'Dosen Pengampu harus diisi!',
        ]);

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
                        'nama' => $barang->ruang->nama,
                    ),
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
            alert()->error('Error!', 'Gagal membuat Peminjaman!');
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_anggota', $error_anggota)
                ->with('data_anggotas', $data_anggotas)
                ->with('error_barang', $error_barang)
                ->with('data_items', $data_items)
                ->withErrors($validator->errors());
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
        if (auth()->user()->telp == null) {
            return false;
        } else {
            return true;
        }
    }
}
