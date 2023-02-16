<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Satuan;
use App\Models\StokBahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokBahanController extends Controller
{
    public function index()
    {
        $stoks = StokBahan::orderByDesc('created_at')->paginate(10);

        return view('admin.stokbahan.index', compact('stoks'));
    }

    public function create()
    {
        $bahans = Bahan::orderBy('nama', 'ASC')->get();
        $satuans = Satuan::where('kategori', '!=', 'barang')->get();

        return view('admin.stokbahan.create', compact('bahans', 'satuans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bahan_id' => 'required',
            'stok' => 'required',
        ], [
            'bahan_id.required' => 'Nama bahan harus dipilih!',
            'stok.required' => 'Stok harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        StokBahan::create($request->all());

        $bahan = Bahan::where('id', $request->bahan_id)->first();
        $satuan = Satuan::where('id', $request->satuan_id)->first();

        $kali = $bahan->satuan->kali / $satuan->kali;
        $stok = $request->stok * $kali;

        Bahan::where('id', $bahan->id)->update([
            'stok' => $bahan->stok + $stok,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Stok Bahan');

        return redirect('admin/stokbahan');
    }

    public function destroy($id)
    {
        $stokbahan = StokBahan::where('id', $id)->first();
        $bahan = Bahan::where('id', $stokbahan->bahan_id)->first();
        $satuan = Satuan::where('id', $stokbahan->satuan_id)->first();

        $kali = $bahan->satuan->kali / $satuan->kali;
        $stok = $stokbahan->stok * $kali;

        Bahan::where('id', $bahan->id)->update([
            'stok' => $bahan->stok - $stok
        ]);

        $stokbahan->delete();

        alert()->success('Success', 'Berhasil menghapus Stok Bahan');

        return redirect('admin/stokbahan');
    }

    public function satuan($id)
    {
        $bahan = Bahan::where('id', $id)->with('satuan')->first();

        return json_encode($bahan);
    }
}
