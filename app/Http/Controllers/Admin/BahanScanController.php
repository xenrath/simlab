<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\RekapBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BahanScanController extends Controller
{
    public function index()
    {
        return view('admin.bahan.scan');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bahans' => 'required|array',
        ], [
            'bahans.required' => 'Bahan belum ditambahkan!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->with('error', 'Gagal mengirim Pengeluaran!');
        }

        $ids = collect($request->bahans)->pluck('id');
        $bahans = Bahan::whereIn('id', $ids)
            ->select('id', 'nama', 'prodi_id', 'satuan_pinjam')
            ->with('prodi:id,nama')
            ->get()
            ->keyBy('id');

        $old_bahans = [];
        foreach ($request->bahans as $value) {
            $bahan = $bahans->get($value['id']);
            if (!$bahan) continue;

            $old_bahans[] = [
                'id' => $bahan->id,
                'nama' => $bahan->nama,
                'prodi' => [
                    'id' => $bahan->prodi_id,
                    'nama' => $bahan->prodi->nama,
                ],
                'satuan_pinjam' => $bahan->satuan_pinjam,
                'jumlah' => $value['jumlah'],
            ];
        }

        foreach ($old_bahans as $value) {
            RekapBahan::create([
                'bahan_id' => $value['id'],
                'bahan_nama' => $value['nama'],
                'prodi_id' => $value['prodi']['id'],
                'prodi_nama' => $value['prodi']['nama'],
                'jumlah' => $value['jumlah'],
                'satuan' => $value['satuan_pinjam'],
                'status' => 'keluar',
            ]);
        }

        return redirect('admin/bahan')->with('success', 'Berhasil mengirim Pengeluaran');
    }

    public function scan_tambah($kode)
    {
        $bahan = Bahan::where('kode', $kode)
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
}
