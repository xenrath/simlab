<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Praktik;
use App\Models\Ruang;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamanNewController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'menunggu'],
            ['peminjam_id', auth()->user()->id],
        ])->orWhere([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])->whereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->get();

        return view('peminjam.peminjaman-new.index', compact('pinjams'));
    }

    public function create()
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect("peminjam");
        }

        if (!$this->jam_kerja()) {
            alert()->error('Error!', 'Anda sedang tidak dalam waktu kerja!');
            return back();
        }

        $ruangs = Ruang::where([
            ['kode', '!=', '01'],
            ['kode', '!=', '02']
        ])->orderBy('kode', 'ASC')->get();
        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->where('normal', '>', '0')->orderBy('ruang_id', 'ASC')->get();
        $bahans = Bahan::whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->where('stok', '>', '0')->orderBy('nama', 'ASC')->get();

        $subprodi_id = auth()->user()->subprodi_id;
        $peminjams = User::where([
            ['id', '!=', auth()->user()->id],
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            // ['telp', '!=', null],
            // ['alamat', '!=', null]
        ])->get();

        $laborans = User::where('role', 'laboran')->whereHas('ruangs', function ($query) {
            $query->where([
                ['tempat_id', '1'],
                ['prodi_id', '!=', '5'],
                ['prodi_id', '!=', '6']
            ])->orderBy('prodi_id', 'ASC');
        })->get();

        $praktiks = Praktik::get();

        return view('peminjam.peminjaman-new.create1', compact(
            'ruangs',
            'barangs',
            'bahans',
            'peminjams',
            'laborans',
            'praktiks'
        ));
    }

    public function store(Request $request)
    {
        $praktik_id = $request->praktik_id;

        if ($praktik_id == '1' && $request->jam == 'lainnya') {
            $validator_peminjaman = Validator::make($request->all(), [
                'anggota' => 'required',
                'tanggal' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'ruang_id' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'keterangan' => 'required',
            ], [
                'anggota.required'  => 'Anggota kelompok harus ditambahkan!',
                'tanggal.required' => 'Waktu praktik harus diisi!',
                'jam_awal.required' => 'Jam awal harus dipilih!',
                'jam_akhir.required' => 'Jam akhir harus dipilih!',
                'ruang_id.required' => 'Ruang (lab) harus dipilih!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
            ]);
        } elseif ($praktik_id == '2' && $request->jam == 'lainnya') {
            $validator_peminjaman = Validator::make($request->all(), [
                'anggota' => 'required',
                'tanggal' => 'required',
                'jam_awal' => 'required',
                'jam_akhir' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'keterangan' => 'required',
                'laboran_id' => 'required'
            ], [
                'anggota.required'  => 'Anggota kelompok harus ditambahkan!',
                'tanggal.required' => 'Waktu praktik harus diisi!',
                'jam_awal.required' => 'Jam awal harus dipilih!',
                'jam_akhir.required' => 'Jam akhir harus dipilih!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
                'laboran_id.required' => 'Laboran harus dipilih!',
            ]);
        } elseif ($praktik_id == '1') {
            $validator_peminjaman = Validator::make($request->all(), [
                'anggota' => 'required',
                'tanggal' => 'required',
                'jam' => 'required',
                'ruang_id' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'keterangan' => 'required',
            ], [
                'anggota.required'  => 'Anggota kelompok harus ditambahkan!',
                'tanggal.required' => 'Waktu praktik harus diisi!',
                'jam.required' => 'Jam praktik harus dipilih!',
                'ruang_id.required' => 'Ruang (lab) harus dipilih!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
            ]);
        } elseif ($praktik_id == '2') {
            $validator_peminjaman = Validator::make($request->all(), [
                'anggota' => 'required',
                'tanggal' => 'required',
                'jam' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'keterangan' => 'required',
                'laboran_id' => 'required'
            ], [
                'anggota.required'  => 'Anggota kelompok harus ditambahkan!',
                'tanggal.required' => 'Tanggal praktik harus diisi!',
                'jam.required' => 'Jam praktik harus dipilih!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
                'laboran_id.required' => 'Laboran harus dipilih!',
            ]);
        } elseif ($praktik_id == '3') {
            $validator_peminjaman = Validator::make($request->all(), [
                'lama' => 'required',
                'matakuliah' => 'required',
                'dosen' => 'required',
                'keterangan' => 'required',
                'laboran_id' => 'required'
            ], [
                'lama.required' => 'Lama peminjaman harus diisi!',
                'matakuliah.required' => 'Mata kuliah harus diisi!',
                'dosen.required' => 'Dosen pengampu harus diisi!',
                'keterangan.required' => 'Keterangan harus diisi!',
                'laboran_id.required' => 'Laboran harus dipilih!',
            ]);
        }
        
        $barang_id = $this->toArray(collect($request->barang_id));
        $jumlah = $this->toArray(collect($request->jumlah));

        $arr_jumlah = array();
        $item = json_encode(array());
        $item_id = array();

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            $item = $this->pilih($barang_id);
            $item_id = $item->pluck('id');

            for ($i = 0; $i < count($item); $i++) {
                $arr_jumlah[] = array('barang_id' => $barang_id[$i], 'jumlah' => $jumlah[$i]);
            }
        }

        if ($validator_peminjaman->fails()) {
            $error_peminjaman = $validator_peminjaman->errors()->all();
        } else {
            $error_peminjaman = null;
        }

        if (count($barang_id) == 0) {
            $empty_barang = array('Barang belum ditambahkan!');
        } else {
            $empty_barang = null;
        }

        if ($error_peminjaman || $empty_barang) {
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('empty_barang', $empty_barang)
                ->with('item', json_decode($item))
                ->with('item_id', collect($item_id))
                ->with('jumlah', collect($arr_jumlah));
        }

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                if ($jumlah[$i] > $barang->normal) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return back();
                }
            }
        }

        if ($praktik_id == '1') {
            $tanggal = $request->tanggal;
            $tanggal_awal = $tanggal;
            $tanggal_akhir = $tanggal;
            if ($request->jam == 'lainnya') {
                $jam_awal = $request->jam_awal;
                $jam_akhir = $request->jam_akhir;
            } else {
                $jam_awal = substr($request->jam, 0, 5);
                $jam_akhir = substr($request->jam, -5);
            }
            $ruang_id = $request->ruang_id;
            $laboran_id = null;
            $anggota = $request->anggota;
        } elseif ($praktik_id == '2') {
            $tanggal = $request->tanggal;
            $tanggal_awal = $tanggal;
            $tanggal_akhir = $tanggal;
            $jam_awal = substr($request->jam, 0, 5);
            $jam_akhir = substr($request->jam, -5);
            $ruang_id = null;
            $laboran_id = $request->laboran_id;
            $anggota = $request->anggota;
        } elseif ($praktik_id == '3') {
            $tanggal_awal = Carbon::now()->format('Y-m-d');
            $tanggal_akhir = Carbon::now()->addDays($request->lama)->format('Y-m-d');
            $jam_awal = null;
            $jam_akhir = null;
            $ruang_id = null;
            $laboran_id = $request->laboran_id;
            $anggota = null;
        }

        $pinjam = Pinjam::create([
            'peminjam_id' => auth()->user()->id,
            'praktik_id' => $request->praktik_id,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'jam_awal' => $jam_awal,
            'jam_akhir' => $jam_akhir,
            'matakuliah' => $request->matakuliah,
            'dosen' => $request->dosen,
            'ruang_id' => $ruang_id,
            'keterangan' => $request->keterangan,
            'laboran_id' => $laboran_id,
            'bahan' => $request->bahan,
            'kategori' => 'normal',
            'status' => 'menunggu'
        ]);

        if ($praktik_id != '3') {
            Kelompok::create(array_merge([
                'pinjam_id' => $pinjam->id,
                'ketua' => $request->ketua,
                'anggota' => $anggota,
            ]));
        }

        if (count($barang_id) > 0 && count($jumlah) > 0) {
            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                DetailPinjam::create(array_merge([
                    'pinjam_id' => $pinjam->id,
                    'barang_id' => $barang->id,
                    'jumlah' => $jumlah[$i],
                    'satuan_id' => '6'
                ]));

                $stok = $barang->normal - $jumlah[$i];

                Barang::where('id', $barang->id)->update([
                    'normal' => $stok
                ]);
            }
        }

        alert()->success('Success', 'Berhasil mengajukan Peminjaman');

        return redirect('peminjam/normal/peminjaman-new');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['status', 'menunggu'],
        ])->first();

        // if (!$pinjam) {
        //     abort(404);
        // }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('peminjam.peminjaman-new.show', compact('pinjam', 'detail_pinjams'));
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->forceDelete();
        if (count($kelompoks)) {
            foreach ($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }
        if ($detailpinjams) {
            foreach ($detailpinjams as $detailpinjam) {
                $barang = Barang::where('id', $detailpinjam->barang_id)->first();

                $barang->update([
                    'normal' => $barang->normal + $detailpinjam->jumlah
                ]);

                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return redirect('peminjam/normal/peminjaman-new');
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

        $pdf = Pdf::loadview('peminjam.peminjaman-new.cetak', compact('pinjam', 'barangs'));

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

    public function pilih($items)
    {
        if ($items) {
            $barangs = collect();
            
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
}
