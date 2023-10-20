<?php

namespace App\Http\Controllers\Peminjam\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Pinjam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LuarController extends Controller
{
    public function index()
    {
        $prodi_id = auth()->user()->subprodi->prodi_id;
        $laborans = User::where('role', 'laboran')->whereHas('ruangs', function ($query) use ($prodi_id) {
            $query->where([
                ['tempat_id', '1'],
                ['prodi_id', $prodi_id],
            ])->orderBy('prodi_id', 'ASC');
        })
            ->select('id', 'nama')
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

        return view('peminjam.peminjaman-new.luar.index', compact('laborans', 'barangs', 'peminjams'));
    }

    public function store(Request $request)
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

        if (count($error_peminjaman) > 0 || count($error_barang) > 0) {
            return back()->withInput()
                ->with('data_items', $data_items)
                ->with('error_peminjaman', $error_peminjaman)
                ->with('error_barang', $error_barang);
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

        return redirect('peminjam/normal/peminjaman-new');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id',  '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
            ->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select('barangs.nama as barang_nama', 'detail_pinjams.jumlah')
            ->get();

        return view('peminjam.peminjaman-new.luar.show', compact('pinjam', 'detail_pinjams'));
    }

    public function edit($id)
    {
        $pinjam = Pinjam::where('pinjams.id', $id)
            ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
            ->join('users', 'pinjams.laboran_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'praktiks.nama as praktik_nama',
                'users.nama as laboran_nama',
                'pinjams.matakuliah',
                'pinjams.praktik',
                'pinjams.dosen',
                'pinjams.kelas',
                'pinjams.keterangan',
                'pinjams.bahan'
            )
            ->first();
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

        return view('peminjam.peminjaman-new.luar.edit', compact('pinjam', 'detail_pinjams', 'barangs'));
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
