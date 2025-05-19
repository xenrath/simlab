<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\DetailPinjam;
use App\Models\Kelompok;
use App\Models\Pinjam;
use App\Models\TagihanPeminjaman;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index()
    {
        if (auth()->user()->ruangs->first()->tempat_id == '1') {
            return $this->index_lab_terpadu();
        } elseif (auth()->user()->ruangs->first()->tempat_id == '2') {
            return $this->index_farmasi();
        }
    }

    public function index_lab_terpadu()
    {
        $pinjams = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'selesai']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->paginate(10);

        return view('laboran.laporan.index_lab_terpadu', compact('pinjams'));
    }

    public function index_farmasi()
    {
        $pinjams = Pinjam::where('status', 'selesai')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->select(
                'id',
                'praktik_id',
                'peminjam_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'kategori',
            )
            ->with('praktik:id,nama', 'peminjam:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->paginate(10);

        return view('laboran.laporan.index_farmasi', compact('pinjams'));
    }

    public function show($id)
    {
        $praktik_id = Pinjam::where('id', $id)->value('praktik_id');

        if ($praktik_id == 1) {
            return $this->show_lab($id);
        } elseif ($praktik_id == 2) {
            return $this->show_kelas($id);
        } elseif ($praktik_id == 3) {
            return $this->show_luar($id);
        } elseif ($praktik_id == 4) {
            return $this->show_ruang($id);
        }
    }

    public function show_lab($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'bahan'
            )
            ->with('praktik:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
            })
            ->first();
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();

        return view('laboran.laporan.show_lab', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans'
        ));
    }

    public function show_kelas($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'laboran_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'keterangan',
                'bahan'
            )
            ->with('praktik:id,nama', 'laboran:id,nama')
            ->first();
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
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();

        return view('laboran.laporan.show_kelas', compact(
            'pinjam',
            'data_kelompok',
            'detail_pinjams',
            'tagihan_peminjamans'
        ));
    }

    public function show_luar($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'laboran_id',
                'tanggal_awal',
                'tanggal_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'keterangan',
                'bahan'
            )
            ->with('praktik:id,nama', 'laboran:id,nama')
            ->first();
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('jumlah', 'barang_id')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();
        $tagihan_peminjamans = TagihanPeminjaman::where('pinjam_id', $id)
            ->select(
                'id',
                'detail_pinjam_id',
                'jumlah',
                'created_at'
            )
            ->with('detail_pinjam', function ($query) {
                $query->select('id', 'barang_id');
                $query->with('barang', function ($query) {
                    $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
                });
            })
            ->get();

        return view('laboran.laporan.show_luar', compact(
            'pinjam',
            'detail_pinjams',
            'tagihan_peminjamans'
        ));
    }

    public function show_ruang($id)
    {
        $pinjam = Pinjam::where('id', $id)
            ->select(
                'id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'praktik as praktik_keterangan',
                'dosen',
                'kelas',
                'bahan'
            )
            ->with('praktik:id,nama')
            ->with('ruang', function ($query) {
                $query->select('id', 'nama', 'laboran_id')->with('laboran:id,nama');
            })
            ->first();
        $kelompok = Kelompok::where('pinjam_id', $id)->select('ketua', 'anggota')->first();
        if ($kelompok) {
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
            $data_kelompok = array();
        }
        $detail_pinjams = DetailPinjam::where('pinjam_id', $id)
            ->select('barang_id', 'jumlah')
            ->with('barang', function ($query) {
                $query->select('id', 'nama', 'ruang_id')->with('ruang:id,nama');
            })
            ->get();

        return view('laboran.laporan.show_ruang', compact('pinjam', 'data_kelompok', 'detail_pinjams'));
    }

    public function print()
    {
        if (auth()->user()->ruangs->first()->tempat_id == '1') {
            return $this->print_lab_terpadu();
        } elseif (auth()->user()->ruangs->first()->tempat_id == '2') {
            // return $this->print_farmasi();
        }
    }

    public function print_lab_terpadu()
    {
        $pinjams = Pinjam::where('kategori', 'normal')
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'keterangan',
            )
            ->with('peminjam:id,nama', 'praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->take(100)
            ->get();

        $pdf = Pdf::loadview('laboran.laporan.print_lab_terpadu', compact('pinjams'));
        $pdf->output();

        // Parameter
        $x = 296;
        $y = 814;
        $text = "{PAGE_NUM}";
        $font = $pdf->getFontMetrics()->get_font('Helvetica', 'normal');
        $size = 10;
        $color = array(0, 0, 0);
        $word_space = 0.0;
        $char_space = 0.0;
        $angle = 0.0;

        $pdf->getCanvas()->page_text(
            $x,
            $y,
            $text,
            $font,
            $size,
            $color,
            $word_space,
            $char_space,
            $angle
        );

        return $pdf->stream('Laporan Peminjaman');
    }

    public function print_lab(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
        ], [
            'tanggal_awal.required' => 'Tanggal Awal harus diisi!',
            'tanggal_akhir.required' => 'Tanggal Akhir harus diisi!',
        ]);
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal melakukan Print Laporan!');
            return back()->withInput()->withErrors($validator->errors());
        }
        // 
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        // 
        $pinjams = Pinjam::where('kategori', 'normal')
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->select(
                'id',
                'peminjam_id',
                'praktik_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'keterangan',
            )
            ->with('peminjam', function ($query) {
                $query->select('id', 'nama', 'kode', 'subprodi_id');
                $query->with('subprodi:id,jenjang,nama');
            })
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->take(100)
            ->get();
        // 
        $pdf = Pdf::loadview('laboran.laporan.show_print', compact('pinjams', 'tanggal_awal', 'tanggal_akhir'));
        // $pdf = Pdf::loadview('laboran.laporan.print_farmasi', compact('pinjams'));
        return $pdf->stream('Laporan Peminjaman');
    }

    public function print_farmasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_awal' => 'required',
            'tanggal_akhir' => 'required',
        ], [
            'tanggal_awal.required' => 'Tanggal Awal harus diisi!',
            'tanggal_akhir.required' => 'Tanggal Akhir harus diisi!',
        ]);
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal melakukan Print Laporan!');
            return back()->withInput()->withErrors($validator->errors());
        }
        // 
        $tanggal_awal = $request->tanggal_awal;
        $tanggal_akhir = $request->tanggal_akhir;
        // 
        $pinjams = Pinjam::where('status', 'selesai')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->where('tanggal_awal', '>=', $request->tanggal_awal)
            ->where('tanggal_akhir', '<=', $request->tanggal_akhir)
            ->select(
                'id',
                'praktik_id',
                'peminjam_id',
                'ruang_id',
                'tanggal_awal',
                'tanggal_akhir',
                'jam_awal',
                'jam_akhir',
                'matakuliah',
                'kategori',
            )
            ->with('peminjam', function ($query) {
                $query->select('id', 'nama', 'kode', 'subprodi_id');
                $query->with('subprodi:id,jenjang,nama');
            })
            ->with('praktik:id,nama', 'ruang:id,nama')
            ->orderByDesc('id')
            ->take(10)
            ->get();
        // 
        $pdf = Pdf::loadview('laboran.laporan.show_print', compact('pinjams', 'tanggal_awal', 'tanggal_akhir'));
        // $pdf = Pdf::loadview('laboran.laporan.print_farmasi', compact('pinjams'));
        return $pdf->stream('Laporan Peminjaman');
    }
}
