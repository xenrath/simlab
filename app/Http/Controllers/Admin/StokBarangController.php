<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\StokBarangsImport;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Satuan;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StokBarangController extends Controller
{
    public function index(Request $request)
    {
        $prodi_id = $request->prodi_id;

        if ($prodi_id != "") {
            $stoks = StokBarang::whereHas('barang', function ($query) use ($prodi_id) {
                $query->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                });
            })->orderBy('created_at', 'DESC')->paginate(10);    
        } else {
            $stoks = StokBarang::orderBy('created_at', 'DESC')->paginate(10);
        }

        $prodis = Prodi::where([
            ['id', '!=', '5'],
            ['id', '!=', '6']
        ])->get();

        return view('admin.stokbarang.index', compact('stoks', 'prodis'));
    }

    public function create()
    {
        $barangs = Barang::orderBy('ruang_id', 'ASC')->orderBy('nama', 'ASC')->get();
        $satuans = Satuan::where('kategori', 'barang')->get();

        return view('admin.stokbarang.create', compact('barangs', 'satuans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required',
            'normal' => 'required',
            'rusak' => 'required',
        ], [
            'barang_id.required' => 'Nama barang harus dipilih!',
            'normal.required' => 'Jumlah baik harus diisi!',
            'rusak.required' => 'Jumlah rusak harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        StokBarang::create(array_merge($request->all(), [
            'satuan_id' => '6'
        ]));

        $barang = Barang::where('id', $request->barang_id)->first();

        Barang::where('id', $request->barang_id)->update([
            'normal' => $barang->normal + $request->normal,
            'rusak' => $barang->rusak + $request->rusak,
            'total' => $barang->total + $request->normal + $request->rusak,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Stok Barang');

        return redirect('admin/stokbarang');
    }

    public function destroy($id)
    {
        $stok = StokBarang::where('id', $id)->first();
        $barang = Barang::where('id', $stok->barang_id)->first();
        $satuan = Satuan::where('id', $stok->satuan_id)->first();

        $kali = $barang->satuan->kali / $satuan->kali;
        $normal = $stok->normal * $kali;
        $rusak = $stok->rusak * $kali;

        Barang::where('id', $barang->id)->update([
            'normal' =>  $barang->normal - $normal,
            'rusak' => $barang->rusak - $rusak,
            'total' => $barang->total - $normal - $rusak,
        ]);

        $stok->delete();

        alert()->success('Success', 'Berhasil menghapus Stok Barang');

        return back();
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import_stokbarang.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ], [
            'file.required' => 'File harus ditambahkan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->with('error', $error);
        }

        $file = $request->file('file');

        $import = new StokBarangsImport;
        $import->import($file);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        }
        
        alert()->success('Success', 'Berhasil menambahkan Stok Barang');

        return back();
    }
}
