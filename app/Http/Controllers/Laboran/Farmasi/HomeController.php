<?php

namespace App\Http\Controllers\Laboran\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    public function index()
    {
        $menunggu = Pinjam::where('status', 'menunggu')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();

        $proses = Pinjam::where('status', 'disetujui')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();

        $tagihan = Pinjam::where('status', 'tagihan')
            ->whereHas('peminjam', function ($query) {
                $query->where('subprodi_id', '5');
            })
            ->count();

        return view('laboran.farmasi.index', compact(
            'menunggu',
            'proses',
            'tagihan'
        ));
    }

    public function bahan_cari(Request $request)
    {
        $nama     = $request->bahan_nama;
        $limit    = (int) $request->input('bahan_page', 10);
        $prodi_id = auth()->user()->prodi_id;

        $bahans = Bahan::where('prodi_id', $prodi_id)
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->when($nama, function ($query) use ($nama) {
                $query->where('nama', 'like', "%{$nama}%");
            })
            ->orderBy('nama')
            ->take($limit)
            ->get();

        return $bahans;
    }

    public function bahan_tambah($id)
    {
        $bahan = Bahan::where('id', $id)
            ->select(
                'id',
                'nama',
                'prodi_id',
                'satuan_pinjam',
            )
            ->with('prodi:id,nama')
            ->first();

        return $bahan;
    }

    public function bahan_scan($kode)
    {
        $bahan = Bahan::where([
            ['kode', $kode],
            ['prodi_id', auth()->user()->prodi_id],
        ])
            ->select('id', 'nama', 'prodi_id', 'satuan_pinjam')
            ->with('prodi:id,nama')
            ->first();

        if ($bahan) {
            return response()->json([
                'success' => true,
                'data' => $bahan
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Bahan tidak ditemukan!'
            ], 200);
        }
    }

    public function hubungi($id)
    {
        $telp = User::where('id', $id)->value('telp');

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $telp);
        }
    }
}
