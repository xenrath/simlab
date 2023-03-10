<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Http\Request;

class PinjamKelompokController extends Controller
{
    public function index()
    {
        // $peminjam = Pinjam::whereHas('kelompoks', function ($query) {
        //     $query->where('ketua', auth()->user()->id)->orWhereIn('anggota', auth()->user()->id);
        // });
        // $pinjams = $peminjam->where('status', '!=', 'selesai')->paginate(10);

        $pinjams = Pinjam::where('peminjam_id', auth()->user()->id)->orWhereHas('kelompoks', function ($query) {
            $query->where('ketua', auth()->user()->kode)->orWhere('anggota', 'like', '%' . auth()->user()->kode . '%');
        })->where('status', 'draft')->where('status', 'menunggu')->get();

        return view('peminjam.kelompok.index', compact('pinjams'));
    }

    public function create()
    {
        $pinjam = new Pinjam();
        $pinjam->peminjam_id = auth()->user()->id;
        $pinjam->status = "draft";
        $pinjam->save();

        return redirect()->to('peminjam/pinjam/kelompok/' . $pinjam->id . '/edit');
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
        $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

        return view('peminjam.kelompok.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
    }

    public function edit($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $shifts = Shift::get();
        $prodi_id = auth()->user()->subprodi->prodi_id;
        $subprodi_id = auth()->user()->subprodi_id;
        $ruangs = Ruang::where('prodi_id', $prodi_id)->get();

        $peminjams = User::where([
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi_id],
            // ['telp', '!=', null],
            // ['alamat', '!=', null]
        ])->get();
        $barangs = Barang::where('normal', '!=', '0')->whereHas('ruang', function ($query) use ($ruangs) {
            $query->where('tempat_id', $ruangs->first()->tempat_id)->orderBy('kode', 'ASC');
        })->get();

        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        return view('peminjam.kelompok.create', compact(
            'ruangs',
            'shifts',
            'peminjams',
            'barangs',
            'pinjam',
            'kelompoks',
            'detailpinjams',
        ));
    }

    public function update(Request $request, $id)
    {
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();

        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        $matakuliah = $request->matakuliah;
        $dosen = $request->dosen;
        $ruang_id = $request->ruang_id;
        $keterangan = $request->keterangan;

        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        if ($barang_id) {
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
            $kelompoks != null &&
            $tanggal_awal != "" &&
            $tanggal_akhir != "" &&
            $matakuliah != "" &&
            $dosen != "" &&
            $ruang_id != "" &&
            $barangx != ""
        ) {
            $status = 'menunggu';
        } else {
            $status = 'draft';
        }

        $id = Pinjam::where('id', $id)->update([
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'matakuliah' => $matakuliah,
            'dosen' => $dosen,
            'ruang_id' => $ruang_id,
            'keterangan' => $keterangan,
            'bahan' => $request->bahan,
            'status' => $status
        ]);

        // $tanggal_kembali = Carbon::parse($request->tanggal_kembali);
        // $tanggal_pinjam = Carbon::parse($request->tanggal_pinjam)->addDays(5);

        if (!($tanggal_awal &&
            $tanggal_akhir &&
            $matakuliah &&
            $dosen &&
            $ruang_id &&
            $barangx
        )) {
            alert()->warning('Peringatan', 'Peminjaman menjadi draft! Lengkapi data untuk menyimpannya.');
            return redirect('peminjam/pinjam/kelompok');;
        }

        alert()->success('Success', 'Berhasil menyimpan peminjaman');

        return redirect('peminjam/pinjam/kelompok');
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();
        $kelompoks = Kelompok::where('pinjam_id', $id)->get();
        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->delete();
        if (count($kelompoks)) {
            foreach($kelompoks as $kelompok) {
                $kelompok->delete();
            };
        }
        if ($detailpinjams) {
            foreach($detailpinjams as $detailpinjam) {
                $detailpinjam->delete();
            };
        }

        return redirect('peminjam/pinjam/kelompok');
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

    public function pilih(Request $request)
    {
        $items = $request->items;

        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
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

            return view('peminjam.kelompok.riwayat.index', compact('pinjams'));
        } else {
            $pinjam = Pinjam::where('id', $id)->first();
            $detailpinjams = DetailPinjam::where('pinjam_id', $pinjam->id)->get();
            $kelompoks = Kelompok::where('pinjam_id', $pinjam->id)->get();

            return view('peminjam.kelompok.riwayat.show', compact('pinjam', 'detailpinjams', 'kelompoks'));
        }
    }
}
