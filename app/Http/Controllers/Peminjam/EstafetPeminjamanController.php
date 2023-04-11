<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EstafetPeminjamanController extends Controller
{
    public function index()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'estafet'],
            ['status', 'menunggu'],
            ['peminjam_id', auth()->user()->id]
        ])->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where([
            ['kategori', 'estafet'],
            ['status', 'menunggu'],
        ])->get();

        return view('peminjam.estafet.peminjaman.index', compact('pinjams'));
    }

    public function create()
    {
        if (!$this->check()) {
            alert()->error('Error!', 'Lengkapi data diri anda terlebih dahulu!');
            return redirect("peminjam");
        }

        $pinjam = new Pinjam;
        $pinjam->peminjam_id = auth()->user()->id;
        $pinjam->praktik_id = "1";
        $pinjam->kategori = "estafet";
        $pinjam->status = "draft";
        $pinjam->save();

        return redirect()->to('peminjam/estafet/peminjaman/' . $pinjam->id . '/edit');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        return view('peminjam.estafet.peminjaman.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
    }

    public function edit($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $prodi_id = auth()->user()->subprodi->prodi_id;
        $subprodi_id = auth()->user()->subprodi_id;
        $ruangs = Ruang::where('prodi_id', $prodi_id)->get();

        $peminjams = User::where([
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            // ['telp', '!=', null],
            // ['alamat', '!=', null]
        ])->get();
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) use ($ruangs) {
            $query->where('tempat_id', $ruangs->first()->tempat_id);
        })->orderBy('ruang_id')->get();

        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('peminjam.estafet.peminjaman.create', compact(
            'ruangs',
            'peminjams',
            'barangs',
            'pinjam',
            'kelompoks',
            'detailpinjams',
        ));
    }

    public function update1(Request $request, $id)
    {
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();

        $matakuliah = $request->matakuliah;
        $dosen = $request->dosen;
        $ruang_id = $request->ruang_id;
        $keterangan = $request->keterangan;
        $bahan = $request->bahan;

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if ($barang_id != "" && $jumlah != 0 && $satuan != "") {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;

                // return $js;

                if ($js > $barang->normal) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return redirect()->back();
                }
                //  else if ($tanggal_kembali > $tanggal_pinjam) {
                //     alert()->error('Error!', 'Maksimal peminjaman 5 Hari!');
                //     return redirect()->back();
                // }
            }

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;

                DetailPinjam::create(array_merge([
                    'pinjam_id' => $id,
                    'barang_id' => $barang->id,
                    'jumlah' => $js,
                    'satuan_id' => $sa->id
                ]));

                $stok = $barang->normal - $js;

                Barang::where('id', $barang->id)->update([
                    'normal' => $stok
                ]);
            }
        }

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        if ($detailpinjams) {
            $barangx = $detailpinjams;
        } else {
            $barangx = $barang_id;
        }

        if (
            $kelompoks != "" &&
            $matakuliah != "" &&
            $dosen != "" &&
            $ruang_id != "" &&
            $barangx != "" &&
            $bahan != ""
        ) {
            $status = 'menunggu';
        } else {
            $status = 'draft';
        }

        $waktu = Carbon::now()->addDays($request->waktu)->format('Y-m-d');

        $id = Pinjam::where('id', $id)->update([
            'tanggal_awal' => $waktu,
            'tanggal_akhir' => $waktu,
            'matakuliah' => $matakuliah,
            'dosen' => $dosen,
            'ruang_id' => $ruang_id,
            'keterangan' => $keterangan,
            'bahan' => $request->bahan,
            'status' => $status
        ]);

        // $tanggal_kembali = Carbon::parse($request->tanggal_kembali);
        // $tanggal_pinjam = Carbon::parse($request->tanggal_pinjam)->addDays(5);

        if (!($matakuliah &&
            $dosen &&
            $ruang_id &&
            $barangx &&
            $bahan
        )) {
            alert()->warning('Peringatan', 'Peminjaman menjadi draft! Lengkapi data untuk menyimpannya.');
            return redirect('peminjam/estafet/peminjaman');;
        }

        alert()->success('Success', 'Berhasil menyimpan peminjaman');

        return redirect('peminjam/estafet/peminjaman');
    }

    public function update(Request $request, $id)
    {
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();

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

        $validator_peminjaman = Validator::make($request->all(), [
            'matakuliah' => 'required',
            'dosen' => 'required',
            'ruang_id' => 'required',
        ], [
            'matakuliah.required' => 'Mata kuliah harus diisi!',
            'dosen.required' => 'Dosen pengampu harus diisi!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
        ]);

        if ($validator_peminjaman->fails()) {
            $error_peminjaman = $validator_peminjaman->errors()->all();
        } else {
            $error_peminjaman = null;
        }

        if (count($kelompoks) == 0) {
            $empty_kelompok = array('Kelompok belum ditambahkan!');
        } else {
            $empty_kelompok = null;
        }

        if (count($barang_id) == 0) {
            $empty_barang = array('Barang belum ditambahkan!');
        } else {
            $empty_barang = null;
        }

        if ($request->kelompok == 'true') {
            $validasi = $this->kelompok_validasi($request);
            if (count($validasi) == 0) {
                $this->kelompok_create($request, $id);

                return back()->withInput()
                    ->with('item', json_decode($item))
                    ->with('item_id', collect($item_id))
                    ->with('jumlah', collect($arr_jumlah));
            }
            $error_kelompok = $validasi;
        } else {
            $error_kelompok = null;
        }

        if ($error_peminjaman || $empty_kelompok || $error_kelompok || $empty_barang) {
            return back()->withInput()
                ->with('error_peminjaman', $error_peminjaman)
                ->with('empty_kelompok', $empty_kelompok)
                ->with('error_kelompok', $error_kelompok)
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

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();

                DetailPinjam::create(array_merge([
                    'pinjam_id' => $id,
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

        $waktu = Carbon::now()->addDays($request->waktu)->format('Y-m-d');

        Pinjam::where('id', $id)->update([
            'tanggal_awal' => $waktu,
            'tanggal_akhir' => $waktu,
            'matakuliah' => $request->matakuliah,
            'dosen' => $request->dosen,
            'ruang_id' => $request->ruang_id,
            'bahan' => $request->bahan,
            'status' => 'menunggu'
        ]);

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('peminjam/estafet/peminjaman');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        // return response($detailpinjams);

        $pinjam->delete();
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
            };
        }

        alert()->success('Success', 'Berhasil menghapus peminjaman');

        return redirect('peminjam/estafet/peminjaman');
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

    public function konfirmasi($id)
    {
        $pinjam = Pinjam::where([
            ['id', $id],
            ['laboran_id', auth()->user()->id],
        ])->first();

        if (!$pinjam) {
            abort(404);
        }

        Pinjam::where('id', $pinjam->id)->update([
            'status' => 'selesai'
        ]);

        alert()->success('Success', 'Berhasil mengkonfirmasi Peminjaman');

        return redirect('peminjam/pinjam/kelompok/riwayat');
    }

    public function barang($id)
    {
        $ruang = Ruang::where('id', $id)->first();
        if ($ruang->prodi == 'farmasi') {
            $barangs = Barang::where([
                ['kategori', 'barang'],
                ['stok', '>', '0']
            ])->orderBy('nama', 'ASC')->get();
            $bahans = Barang::where([
                ['kategori', 'bahan'],
                ['stok', '>', '0'],
            ])->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = Barang::where([
                ['kategori', 'barang'],
                ['stok', '>', '0']
            ])->orderBy('nama', 'ASC')->get();
            $bahans = Barang::where([
                ['kategori', 'bahan'],
                ['stok', '>', '0'],
            ])->orderBy('nama', 'ASC')->get();
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

    // public function terima($id)
    // {
    //     $pinjam = Pinjam::find($id);
    //     // $barang_pinjams = BarangPinjam::where('pinjam_id', $id)->get();

    //     $terima = $pinjam->update([
    //         'status' => 'diterima'
    //     ]);

    //     if ($terima) {
    //         foreach ($barang_pinjams as $barang_pinjam) {
    //             $barang = Barang::where('id', $barang_pinjam->barang_id)->first();

    //             $stok = $barang->stok - $barang_pinjam->jumlah;

    //             Barang::where('id', $barang_pinjam->id)->update([
    //                 'stok' => $stok
    //             ]);
    //         }
    //     }

    //     alert()->success('Success', 'Berhasil menerima peminjaman');

    //     return redirect()->back();
    // }

    // Diterima

    public function kelompok(Request $request)
    {
        $jumlah = $request->jumlahkelompok;

        $output = "";
        for ($i = 0; $i < $jumlah; $i++) {
            $output .= "<tr>
            <td>
              <input type='text' class='form-control' name='kelompok[" . $i . "]'>
            </td>
            <td>
              <input type='text' class='form-control' name='ketua[" . $i . "]'>
            </td>
            <td>
              <input type='text' class='form-control' name='anggota[" . $i . "]'>
            </td>
          </tr>";
        }

        return json_encode($output);
    }

    public function riwayat($id = null)
    {
        if ($id == null) {
            $pinjams = Pinjam::where([
                ['peminjam_id', null],
                ['status', 'selesai'],
                ['laboran_id', auth()->user()->id]
            ])->paginate(10);

            return view('peminjam.estafet.peminjaman.riwayat.index', compact('pinjams'));
        } else {
            $pinjam = Pinjam::where('id', $id)->first();
            $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
            $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

            return view('peminjam.estafet.peminjaman.riwayat.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
        }
    }

    public function kelompok_create($request, $id)
    {
        Kelompok::create(array_merge([
            'pinjam_id' => $id,
            'nama' => $request->nama_kelompok,
            'ketua' => $request->ketua_kelompok,
            'anggota' => $request->anggota_kelompok,
            'shift' => $request->shift,
            'jam' => $request->jam,
        ]));
    }

    public function kelompok_hapus($id)
    {
        Kelompok::where('id', $id)->delete();
    }

    public function kelompok_validasi($request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelompok' => 'required',
            'ketua_kelompok' => 'required',
            'anggota_kelompok' => 'required',
            'shift' => 'required',
            'jam' => 'required'
        ], [
            'nama_kelompok.required' => 'Nama kelompok tidak boleh kosong!',
            'ketua_kelompok.required' => 'Ketua kelompok tidak boleh kosong!',
            'anggota_kelompok.required' => 'Anggota kelompok tidak boleh kosong!',
            'shift.required' => 'Shift harus dipilih!',
            'jam.required' => 'Jam harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
        } else {
            $error = array();
        }

        return $error;
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
