<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BahansImport;
use App\Models\Bahan;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $tempat = $request->get('tempat');

        if ($tempat != "" && $keyword != "") {
            $bahans = Bahan::select(
                'id',
                'kode',
                'nama',
                'ruang_id'
            )
                ->whereHas('ruang', function ($query) use ($tempat) {
                    $query->where('tempat_id', $tempat);
                })
                ->where('nama', 'LIKE', "%$keyword%")
                ->with('ruang', function ($query) {
                    $query->select('id', 'tempat_id')->with('tempat:id,nama');
                })
                ->orderBy('nama')
                ->paginate(10);
        } elseif ($tempat != "" && $keyword == "") {
            $bahans = Bahan::select(
                'id',
                'kode',
                'nama',
                'ruang_id'
            )
                ->whereHas('ruang', function ($query) use ($tempat) {
                    $query->where('tempat_id', $tempat);
                })
                ->with('ruang', function ($query) {
                    $query->select('id', 'tempat_id')->with('tempat:id,nama');
                })
                ->orderBy('nama')
                ->paginate(10);
        } elseif ($tempat == "" && $keyword != "") {
            $bahans = Bahan::select(
                'id',
                'kode',
                'nama',
                'ruang_id'
            )
                ->where('nama', 'LIKE', "%$keyword%")
                ->with('ruang', function ($query) {
                    $query->select('id', 'tempat_id')->with('tempat:id,nama');
                })
                ->orderBy('nama')
                ->paginate(10);
        } else {
            $bahans = Bahan::select(
                'id',
                'kode',
                'nama',
                'ruang_id'
            )
                ->with('ruang', function ($query) {
                    $query->select('id', 'tempat_id')->with('tempat:id,nama');
                })
                ->orderBy('nama')
                ->paginate(10);
        }

        $tempats = Tempat::select('id', 'nama')->get();

        return view('admin.bahan.index', compact('bahans', 'tempats'));
    }

    public function create()
    {
        $ruangs = Ruang::select('id', 'nama')->get();
        $satuans = Satuan::select('id', 'nama')->where('kategori', '!=', 'barang')->get();

        return view('admin.bahan.create', compact('ruangs', 'satuans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'ruang_id' => 'required',
            'stok' => 'required',
            'satuan_id' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama bahan tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'stok.required' => 'Stok barang harus diisi!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            'gambar.max' => 'Gambar maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = date('YmdHis');

        if ($request->gambar) {
            $gambar = 'bahan/' . $kode . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->storeAs('public/uploads/', $gambar);
        } else {
            $gambar = null;
        }

        Bahan::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'stok' => $request->stok,
            'satuan_id' => $request->satuan_id,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        alert()->success('Success', 'Berhasil menambahkan Bahan');

        return redirect('admin/bahan');
    }

    public function show($id)
    {
        $bahan = Bahan::where('id', $id)
            ->select(
                'kode',
                'nama',
                'ruang_id',
                'stok',
                'satuan_id',
                'keterangan'
            )
            ->with('ruang', function ($query) {
                $query->select('id', 'tempat_id')->with('tempat:id,nama');
            })
            ->with('satuan:id,singkatan')
            ->first();

        return view('admin.bahan.show', compact('bahan'));
    }

    public function edit($id)
    {
        $bahan = Bahan::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'ruang_id',
                'stok',
                'satuan_id',
                'keterangan'
            )
            ->with('ruang', function ($query) {
                $query->select('id', 'tempat_id')->with('tempat:id,nama');
            })
            ->with('satuan:id,singkatan')
            ->first();
        $ruangs = Ruang::select('id', 'nama')->get();
        $satuans = Satuan::select('id', 'nama')->where('kategori', '!=', 'barang')->get();

        return view('admin.bahan.edit', compact('bahan', 'ruangs', 'satuans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:bahans,kode,' . $id,
            'nama' => 'required',
            'ruang_id' => 'required',
            'stok' => 'required',
            'satuan_id' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama bahan tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'stok.required' => 'Stok tidak boleh kosong!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            'gambar.max' => 'Gambar maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $bahan_gambar = Bahan::where('id', $id)->value('gambar');

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $bahan_gambar);
            $gambar = 'bahan/' . $request->kode . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->storeAs('public/uploads/', $gambar);
        } else {
            $gambar = $bahan_gambar;
        }

        Bahan::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'stok' => $request->stok,
            'satuan_id' => $request->satuan_id,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        alert()->success('Success', 'Berhasil memperbarui Bahan');

        return redirect('admin/bahan');
    }

    public function destroy($id)
    {
        Bahan::where('id', $id)->delete();
        alert()->success('Success', 'Berhasil menghapus Bahan');

        return back();
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import_bahan.xlsx');
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

        $import = new BahansImport();
        $import->import($file);

        // dd($import->failures());

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        } else {
            alert()->success('Success', 'Berhasil menambahkan Barang');
        }

        return back();
    }
}
