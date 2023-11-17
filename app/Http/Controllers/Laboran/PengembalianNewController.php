<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\Satuan;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class PengembalianNewController extends Controller
{
    public function index(Request $request)
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->join('users', 'pinjams.peminjam_id', '=', 'users.id')
            ->select(
                'pinjams.id',
                'pinjams.praktik_id',
                'pinjams.ruang_id',
                'users.nama as user_nama',
                'pinjams.tanggal_awal',
                'pinjams.tanggal_akhir',
                'pinjams.jam_awal',
                'pinjams.jam_akhir',
                'pinjams.keterangan',
            )
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->paginate(6);

        return view('laboran.pengembalian-new.index', compact('pinjams'));
    }

    public function show($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $detailpinjams = DetailPinjam::where('pinjam_id', $id)->whereHas('barang', function ($query) {
            $query->orderBy('nama', 'ASC');
        })->get();

        $barangs = Barang::where('normal', '>', '0')->orderBy('ruang_id', 'ASC')->get();

        return view('laboran.pengembalian-new.show', compact('pinjam', 'detailpinjams', 'barangs'));
    }

    public function destroy($id)
    {
        $pinjam = Pinjam::findOrFail($id);
        $kelompok = Kelompok::where('pinjam_id', $id)->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)->get();

        $pinjam->forceDelete();
        if ($kelompok) {
            $kelompok->delete();
        }
        if ($detail_pinjams) {
            foreach ($detail_pinjams as $detailpinjam) {
                $detailpinjam->delete();
            }
        }

        alert()->success('Success', 'Berhasil menghapus Peminjaman');

        return back();
    }

    public function pilih(Request $request)
    {
        $items = $request->items;
        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan')->orderBy('kategori', 'DESC')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }

    public function update(Request $request, $id)
    {
        // Array
        $barang_id = $request->barang_id;
        $jumlah = $request->jumlah;
        $satuan = $request->satuan;

        Pinjam::where('id', $id)->update([
            'bahan' => $request->bahan
        ]);

        if ($barang_id) {
            $barangs = Barang::whereIn('id', $barang_id)->get();

            for ($i = 0; $i < count($barang_id); $i++) {
                $barang = $barangs->where('id', $barang_id[$i])->first();
                $sa = Satuan::where('id', $satuan[$i])->first();
                $kali = $barang->satuan->kali / $sa->kali;
                $js = $jumlah[$i] * $kali;
                if ($js > $barang->stok) {
                    alert()->error('Error!', 'Jumlah barang melebihi stok!');
                    return redirect()->back()->withInput();
                }
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
            }
        }

        alert()->success('Success', 'Berhasil mengajukan peminjaman');

        return back();
    }

    public function konfirmasi($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
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
        } elseif ($praktik_id == 2) {
            $pinjam = Pinjam::where('pinjams.id', $id)
                ->join('praktiks', 'pinjams.praktik_id', '=', 'praktiks.id')
                ->join('users', 'pinjams.laboran_id', '=', 'users.id')
                ->select(
                    'pinjams.id',
                    'pinjams.tanggal_awal',
                    'pinjams.jam_awal',
                    'pinjams.jam_akhir',
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
        } elseif ($praktik_id == 3) {
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
        }

        if ($praktik_id == 1 || $praktik_id == 2) {
            $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
            $ketua = User::where('kode', $kelompok->ketua)->select('kode', 'nama')->first();
            $anggota = array();
            foreach ($kelompok->anggota as $kode) {
                $data_anggota = User::where('kode', $kode)->select('kode', 'nama')->first();
                array_push($anggota, array('kode' => $data_anggota->kode, 'nama' => $data_anggota->nama));
            }
            $data_kelompok = array(
                'ketua' => array('kode' => $ketua->kode, 'nama' => $ketua->nama),
                'anggota' => $anggota
            );
        } else {
            $data_kelompok = null;
        }

        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->select(
                'detail_pinjams.id',
                'detail_pinjams.jumlah',
                'barangs.nama'
            )
            ->get();

        return view('laboran.pengembalian-new.konfirmasi', compact('praktik_id', 'pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function p_konfirmasi(Request $request, $id)
    {
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('id', 'jumlah', 'barang_id')
            ->get();

        $errors = array();
        $datas = array();

        foreach ($detail_pinjams as $detail_pinjam) {
            $barang = Barang::where('id', $detail_pinjam->barang_id)->select('nama')->first();

            $rusak = $request->input('rusak-' . $detail_pinjam->id);
            $hilang = $request->input('hilang-' . $detail_pinjam->id);

            $jumlah = $rusak + $hilang;

            $datas[$detail_pinjam->id] = array('rusak' => $rusak, 'hilang' => $hilang);

            if ($jumlah > $detail_pinjam->jumlah) {
                array_push($errors, '<strong>' . $barang->nama . '</strong>, jumlah penambahan barang rusak dan hilang melebihi jumlah barang yang dipinjam!');
            }
        }

        if (count($errors) > 0) {
            return back()->with('errors', $errors)->with('datas', $datas);
        }

        $tagihan = 0;

        foreach ($detail_pinjams as $detail_pinjam) {
            $barang = Barang::where('id', $detail_pinjam->barang_id)->select('normal', 'rusak', 'hilang')->first();

            $rusak = $request->input('rusak-' . $detail_pinjam->id);
            $hilang = $request->input('hilang-' . $detail_pinjam->id);

            $rusak_hilang = $rusak + $hilang;
            $normal = $detail_pinjam->total - $rusak_hilang;

            if ($rusak_hilang != 0) {
                $tagihan += 1;
                $detail_pinjam_status = false;
            } else {
                $detail_pinjam_status = true;
            }

            $barang_normal = $barang->normal - $rusak_hilang;
            $barang_rusak = $barang->rusak + $rusak;
            $barang_hilang = $barang->hilang + $hilang;

            Barang::where('id', $detail_pinjam->barang_id)->update([
                'normal' => $barang_normal,
                'rusak' => $barang_rusak,
                'hilang' => $barang_hilang,
            ]);

            DetailPinjam::where('id', $detail_pinjam->id)
                ->update([
                    'normal' => $normal,
                    'rusak' => $rusak,
                    'hilang' => $hilang,
                    'status' => $detail_pinjam_status
                ]);
        }

        if ($tagihan > 0) {
            $pinjam_status = 'tagihan';
        } else {
            $pinjam_status = 'selesai';
        }

        $pinjam = Pinjam::where('id', $id)->update([
            'status' => $pinjam_status
        ]);

        if ($pinjam) {
            alert()->success('Success', 'Berhasil mengkonfirmasi peminjaman');
        } else {
            alert()->error('Error', 'Gagal mengkonfirmasi peminjaman!');
        }

        return redirect('laboran/pengembalian-new');
    }

    public function hubungi($id)
    {
        $pinjam = Pinjam::where('id', $id)->first();

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $pinjam->peminjam->telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $pinjam->peminjam->telp);
        }
    }
}
