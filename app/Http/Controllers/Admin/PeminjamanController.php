<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeminjamanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where('laboran_id', auth()->user()->id)->get();

        return view('admin.peminjaman.index', compact('pinjams'));
    }

    public function create()
    {
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->orderBy('ruang_id', 'ASC')->get();
        $peminjams = User::where('kode', null)->get();

        return view('admin.peminjaman.create', compact('barangs', 'peminjams'));
    }

    public function store(Request $request)
    {
        $check = $request->check;
        if ($check) {
            $validator = Validator::make($request->all(), [
                'lama' => 'required',
            ], [
                'lama.required' => 'Lama peminjaman harus diisi!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'alamat' => 'required',
                'nama' => 'required',
                'telp' => 'required|unique:users',
                'lama' => 'required',
            ], [
                'alamat.required' => 'Nama instansi harus diisi!',
                'nama.required' => 'Nama penerima harus diisi!',
                'telp.required' => 'Nomor telepon harus diisi!',
                'telp.unique' => 'Nomor telepon sudah digunakan!',
                'lama.required' => 'Lama peminjaman harus diisi!',
            ]);
        }

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if (!$barang_id) {
            alert()->error('Error', 'Pilih barang terlebih dahulu!');
            return back()->withInput();
        }

        $barangs = Barang::whereIn('id', $barang_id)->get();

        for ($i = 0; $i < count($barang_id); $i++) {
            $barang = $barangs->where('id', $barang_id[$i])->first();
            $sa = Satuan::where('id', $satuan[$i])->first();
            $kali = $barang->satuan->kali / $sa->kali;
            $js = $jumlah[$i] * $kali;

            // return $js;

            if ($js > $barang->normal) {
                alert()->error('Error!', 'Jumlah barang melebihi stok!');
                return back()->withInput();
            }
        }

        if ($check) {
            $peminjam_id = $request->peminjam_id;
            $user = User::where('id', $peminjam_id)->first();
        } else {
            $alamat = $request->alamat;
            $nama = $request->nama;
            $telp = $request->telp;
    
            $user = User::create([
                'username' => '+62' . $telp,
                'nama' => $nama,
                'telp' => $telp,
                'password' => 'simlabBHAMADA',
                'gender' => 'P',
                'alamat' => $alamat,
                'role' => 'peminjam'
            ]);
        }

        $lama = $request->lama;
        $tanggal_awal = Carbon::now()->format('Y-m-d');
        $tanggal_akhir = Carbon::now()->addDays($lama)->format('Y-m-d');

        $pinjam = Pinjam::create(array_merge($request->all(), [
            'peminjam_id' => $user->id,
            'praktik_id' => '3',
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'laboran_id' => auth()->user()->id,
            'kategori' => 'normal',
            'status' => 'disetujui'
        ]));

        for ($i = 0; $i < count($barang_id); $i++) {
            $barang = $barangs->where('id', $barang_id[$i])->first();
            $sa = Satuan::where('id', $satuan[$i])->first();
            $kali = $barang->satuan->kali / $sa->kali;
            $js = $jumlah[$i] * $kali;

            DetailPinjam::create(array_merge([
                'pinjam_id' => $pinjam->id,
                'barang_id' => $barang->id,
                'jumlah' => $js,
                'satuan_id' => $sa->id
            ]));

            $stok = $barang->normal - $js;

            Barang::where('id', $barang->id)->update([
                'normal' => $stok
            ]);
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('admin/peminjaman');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('admin.peminjaman.show', compact('pinjam', 'detail_pinjams'));
    }

    public function konfirmasi_selesai($id)
    {
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'desc');
        })->get();

        foreach ($detailpinjams as $detailpinjam) {
            $barang = Barang::where('id', $detailpinjam->barang_id)->first();

            $barang->update([
                'normal' => $barang->normal + $detailpinjam->jumlah
            ]);

            DetailPinjam::where('id', $detailpinjam->id)
                ->update([
                    'normal' => $detailpinjam->jumlah
                ]);
        }

        $update = Pinjam::where('id', $id)->update([
            'status' => 'selesai'
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman');
        }

        return redirect('admin/peminjaman');
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

        return redirect('peminjam/normal/peminjaman');
    }

    public function pilih(Request $request)
    {
        $items = $request->items;

        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan', 'ruang')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }
}
