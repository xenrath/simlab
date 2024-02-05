<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Ruang;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword) {
            $bahans = Bahan::where('nama', 'LIKE', "%$keyword%")
                ->select(
                    'id',
                    'kode',
                    'nama',
                    'ruang_id'
                )
                ->with('ruang:id,nama')
                ->paginate(10);
        } else {
            $bahans = Bahan::select(
                'id',
                'kode',
                'nama',
                'ruang_id'
            )
                ->with('ruang:id,nama')
                ->paginate(10);
        }

        return view('dev.bahan.index', compact('bahans'));
    }

    public function create()
    {
        $ruangs = Ruang::select('id', 'nama')->get();
        $satuans = Satuan::where('kategori', '!=', 'barang')
            ->select('id', 'nama')
            ->get();

        return view('dev.bahan.create', compact('ruangs', 'satuans'));
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
            'stok.required' => 'Stok tidak boleh kosong!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            'gambar.max' => 'Gambar maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->gambar) {
            $kode = date('YmdHis');
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

        return redirect('dev/bahan');
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
                'keterangan',
                'gambar'
            )
            ->first();
        return view('dev.bahan.show', compact('bahan'));
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
                'keterangan',
                'gambar'
            )
            ->first();
        $ruangs = Ruang::select('id', 'nama')->get();
        $satuans = Satuan::where('kategori', '!=', 'barang')
            ->select('id', 'nama')
            ->get();

        return view('dev.bahan.edit', compact('bahan', 'ruangs', 'satuans'));
    }

    public function update(Request $request, $id)
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
            'stok.required' => 'Stok harus diisi!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $bahan = Bahan::where('id', $id)
            ->select('kode', 'gambar')
            ->first();

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $bahan->gambar);
            $kode = $bahan->kode;
            $gambar = 'bahan/' . $kode . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->storeAs('public/uploads/', $gambar);
        } else {
            $gambar = $bahan->gambar;
        }

        Bahan::where('id', $id)->update([
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'stok' => $request->stok,
            'satuan_id' => $request->satuan_id,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        alert()->success('Success', 'Berhasil memperbarui Bahan');

        return redirect('dev/bahan');
    }

    public function destroy($id)
    {
        Bahan::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus Bahan');

        return back();
    }

    public function trash()
    {
        $bahans = Bahan::onlyTrashed()->select(
            'id',
            'kode',
            'nama',
            'ruang_id'
        )
            ->with('ruang:id,nama')
            ->paginate(10);
        return view('dev.bahan.trash', compact('bahans'));
    }

    public function restore($id = null)
    {
        if ($id != null) {
            Bahan::where('id', $id)->onlyTrashed()->restore();
        } else {
            Bahan::onlyTrashed()->restore();
        }

        alert()->success('Success', 'Berhasil memulihkan Bahan');

        return back();
    }

    public function delete($id = null)
    {
        if ($id != null) {
            Bahan::where('id', $id)->onlyTrashed()->forceDelete();
        } else {
            Bahan::onlyTrashed()->forceDelete();
        }

        alert()->success('Success', 'Berhasil menghapus Bahan');

        return back();
    }
}
