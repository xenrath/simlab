<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BuatController extends Controller
{
    public function index()
    {
        $tempatId = 1;
        $barangs = Barang::whereHas('ruang', function ($query) use ($tempatId) {
            $query->where('tempat_id', $tempatId);
        })
            ->select('id', 'nama', 'ruang_id')
            ->with(['ruang:id,nama'])
            ->orderBy('nama')
            ->take(10)
            ->get();
        $tamus = Tamu::select('id', 'nama', 'institusi')
            ->orderBy('nama')
            ->take(10)
            ->get();
        // 
        return view('admin.buat.index', compact('barangs', 'tamus'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tamu_id' => 'required',
            'lama' => 'required',
            'barangs' => 'required',
        ], [
            'tamu_id.required' => 'Tamu harus dipilih!',
            'lama.required' => 'Lama Peminjaman tidak boleh kosong!',
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
        $tanggal_akhir = Carbon::now()->addDays($request->lama)->format('Y-m-d');
        // 
        $peminjaman = PeminjamanTamu::create([
            'tamu_id' => $request->tamu_id,
            'lama' => $request->lama,
            'keperluan' => $request->keperluan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => 'proses'
        ]);
        // 
        foreach ($request->barangs as $key => $value) {
            DetailPeminjamanTamu::create([
                'peminjaman_tamu_id' => $peminjaman->id,
                'barang_id' => $value['id'],
                'total' => $value['jumlah'],
            ]);
        }
        // 
        alert()->success('Success', 'Berhasil membuat Peminjaman');
        return redirect('admin/proses');
    }
}
