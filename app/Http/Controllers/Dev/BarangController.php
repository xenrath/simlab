<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Ruang;
use App\Models\StokBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword != "") {
            $barangs = Barang::where('nama', 'LIKE', "%$keyword%")
                ->select(
                    'id',
                    'kode',
                    'nama',
                    'ruang_id'
                )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->paginate(10);
        } else {
            $barangs = Barang::select(
                'id',
                'kode',
                'nama',
                'ruang_id'
            )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->paginate(10);
        }

        return view('dev.barang.index', compact('barangs'));
    }

    public function create()
    {
        $ruangs = Ruang::select('id', 'nama')->get();

        return view('dev.barang.create', compact('ruangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'normal.required' => 'Jumlah baik tidak boleh kosong!',
            'gambar.nullable' => 'Gambar harus ditambahkan!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            'gambar.max' => 'Gambar maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = date('YmdHis');

        if ($request->gambar) {
            $gambar = 'barang/' . $kode . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->storeAs('public/uploads/', $gambar);
        } else {
            $gambar = null;
        }

        $barang = Barang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'total' => $request->normal + $request->rusak,
            'normal' => $request->normal,
            'rusak' => $request->rusak,
            'satuan_id' => '6',
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        if ($barang) {
            StokBarang::create(array_merge([
                'barang_id' => $barang->id,
                'normal' => $request->normal,
                'rusak' => $request->rusak,
                'satuan_id' => '6',
            ]));

            alert()->success('Success', 'Berhasil menambahkan Barang');
        } else {
            alert()->error('Error', 'Gagal menambahkan Barang!');
        }

        return redirect('dev/barang');
    }

    public function show($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'kode',
                'nama',
                'ruang_id',
                'normal',
                'rusak',
                'keterangan',
                'gambar'
            )
            ->with('ruang:id,nama')
            ->first();
        return view('dev.barang.show', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'ruang_id',
                'normal',
                'rusak',
                'keterangan',
                'gambar'
            )
            ->with('ruang:id,nama')
            ->first();
        $ruangs = Ruang::select('id', 'nama')->get();

        return view('dev.barang.edit', compact('barang', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required',
            'rusak' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
            'nama.required' => 'Nama barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'normal.required' => 'Jumlah normal harus diisi!',
            'rusak.required' => 'Jumlah rusak harus diisi!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $barang_gambar = Barang::where('id', $id)->value('gambar');

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $barang_gambar);
            $kode = $request->kode;
            $gambar = 'barang/' . $kode . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->storeAs('public/uploads/', $gambar);
        } else {
            $gambar = $barang_gambar;
        }

        Barang::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'total' => $request->normal + $request->rusak,
            'normal' => $request->normal,
            'rusak' => $request->rusak,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        alert()->success('Success', 'Berhasil memperbarui Barang');

        return redirect('dev/barang');
    }

    public function destroy($id)
    {
        Barang::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus Barang');

        return back();
    }

    public function trash()
    {
        $barangs = Barang::onlyTrashed()->paginate(10);
        return view('dev.barang.trash', compact('barangs'));
    }

    public function restore($id = null)
    {
        if ($id != null) {
            Barang::where('id', $id)->onlyTrashed()->restore();
        } else {
            Barang::onlyTrashed()->restore();
        }

        alert()->success('Success', 'Berhasil memulihkan Barang');

        return back();
    }

    public function delete($id = null)
    {
        if ($id != null) {
            $barang = Barang::onlyTrashed()->where('id', $id)->first();

            try {
                $barang->forceDelete();
            } catch (\Throwable $th) {
                return back()->with('error', array('Barang <strong>' . $barang->nama . '</strong> memiliki relasi!'));
            }

            Storage::disk('local')->delete('public/uploads/' . $barang->foto);
        } else {
            $barangs = Barang::onlyTrashed()->get();
            $error  = array();

            foreach ($barangs as $barang) {
                try {
                    $barang->forceDelete();
                } catch (\Throwable $th) {
                    array_push($error, 'Barang <strong>' . $barang->nama . '</strong> memiliki relasi!');
                    continue;
                }
                Storage::disk('local')->delete('public/uploads/' . $barang->gambar);
            }

            if (count($error) > 0) {
                return back()->with('error', $error);
            }
        }

        alert()->success('Success', 'Berhasil menghapus Barang');

        return back();
    }
}
