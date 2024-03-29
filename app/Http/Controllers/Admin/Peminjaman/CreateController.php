<?php

namespace App\Http\Controllers\Admin\Peminjaman;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPeminjamanTamu;
use App\Models\PeminjamanTamu;
use App\Models\Tamu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CreateController extends Controller
{
    public function create()
    {
        $barangs = Barang::where('normal', '>', '0')->whereHas('ruang', function ($query) {
            $query->where('tempat_id', '1');
        })->with('ruang', 'satuan')->orderBy('nama')->get();

        $tamus = Tamu::orderBy('institusi')->orderBy('nama')->get();

        return view('admin.peminjaman.create.index', compact('barangs', 'tamus'));
    }

    public function store(Request $request)
    {
        $validator_peminjaman = Validator::make($request->all(), [
            'tamu_id' => 'required',
            'lama' => 'required',
        ], [
            'tamu_id.required' => 'Tamu harus dipilih!',
            'lama.required' => 'Lama Peminjaman tidak boleh kosong!',
        ]);

        $errors = array();

        if ($validator_peminjaman->fails()) {
            foreach ($validator_peminjaman->errors()->all() as $error) {
                array_push($errors, $error);
            }
        }

        $items = $request->items;
        $data_items = array();

        if (!is_null($items)) {
            foreach ($items as $barang_id => $total) {
                $barang = Barang::where('id', $barang_id)
                    ->select('nama', 'ruang_id')
                    ->with('ruang:id,nama')
                    ->first();
                array_push($data_items, array(
                    'id' => $barang_id,
                    'nama' => $barang->nama,
                    'ruang_nama' => $barang->ruang->nama,
                    'total' => $total
                ));
            }
        } else {
            array_push($errors, 'Barang belum ditambahkan!');
        }

        if (count($errors) > 0) {
            return back()->withInput()
                ->with('data_items', $data_items)
                ->with('errors', $errors);
        }

        $tanggal_awal = Carbon::now()->format('Y-m-d');
        $tanggal_akhir = Carbon::now()->addDays($request->lama)->format('Y-m-d');

        $peminjaman = PeminjamanTamu::create(array_merge($request->all(), [
            'tamu_id' => $request->tamu_id,
            'lama' => $request->lama,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'laboran_id' => auth()->user()->id,
            'kategori' => 'normal',
            'status' => 'proses'
        ]));

        foreach ($items as $barang_id => $total) {
            DetailPeminjamanTamu::create(array_merge([
                'peminjaman_tamu_id' => $peminjaman->id,
                'barang_id' => $barang_id,
                'total' => $total,
            ]));
        }

        alert()->success('Success', 'Berhasil membuat Peminjaman');

        return redirect('admin/peminjaman/proses');
    }
}
