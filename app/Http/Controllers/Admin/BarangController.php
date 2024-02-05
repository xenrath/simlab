<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BarangsImport;
use App\Imports\UpdateKodesImport;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\StokBarang;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $prodi_id = $request->get('prodi_id');

        if ($prodi_id != "" && $keyword != "") {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->where('nama', 'LIKE', "%$keyword%")
                ->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })
                ->with('ruang', function ($query) {
                    $query->select('id', 'prodi_id')->with('prodi:id,singkatan');
                })
                ->orderBy('nama')
                ->paginate(10);
        } elseif ($prodi_id != "" && $keyword == "") {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->whereHas('ruang', function ($query) use ($prodi_id) {
                    $query->where('prodi_id', $prodi_id);
                })
                ->with('ruang', function ($query) {
                    $query->select('id', 'prodi_id')->with('prodi:id,singkatan');
                })
                ->orderBy('nama')
                ->paginate(10);
        } elseif ($prodi_id == "" && $keyword != "") {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->where('nama', 'LIKE', "%$keyword%")
                ->with('ruang', function ($query) {
                    $query->select('id', 'prodi_id')->with('prodi:id,singkatan');
                })
                ->orderBy('nama')
                ->paginate(10);
        } else {
            $barangs = Barang::select(
                'id',
                'nama',
                'ruang_id'
            )
                ->with('ruang', function ($query) {
                    $query->select('id', 'prodi_id')->with('prodi:id,singkatan');
                })
                ->orderBy('nama')
                ->paginate(10);
        }

        $prodis = Prodi::where('is_prodi', true)->select('id', 'singkatan')->get();

        return view('admin.barang.index', compact('barangs', 'prodis'));
    }

    public function create()
    {
        $ruangs = Ruang::select('id', 'nama')->get();

        return view('admin.barang.create', compact('ruangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama Barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
            'normal.required' => 'Jumlah baik tidak boleh kosong!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            'gambar.max' => 'Gambar maksimal ukuran 2MB',
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

        Barang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'total' => $request->normal + $request->rusak,
            'normal' => $request->normal,
            'rusak' => $request->rusak,
            'hilang' => $request->hilang,
            'satuan_id' => 6,
            'keterangan' => $request->keterangan,
            'gambar' => $gambar
        ]);

        alert()->success('Success', 'Berhasil menambahkan Barang');
        
        return redirect('admin/barang');
    }

    public function show($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'kode',
                'nama',
                'ruang_id',
                'total',
                'normal',
                'rusak',
                'keterangan',
                'gambar'
            )
            ->with('ruang:id,nama')
            ->first();

        return view('admin.barang.show', compact('barang'));
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
        $ruangs = Ruang::get();

        return view('admin.barang.edit', compact('barang', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:barangs,kode,' . $id,
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama Barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
            'normal.required' => 'Jumlah baik harus diisi!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            'gambar.max' => 'Gambar maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $request->gambar);
            $gambar = 'barang/' . $request->kode . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->gambar->storeAs('public/uploads/', $gambar);
        } else {
            $gambar = Barang::where('id', $id)->value('gambar');
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

        return redirect('admin/barang');
    }

    public function destroy($id)
    {
        Barang::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus Barang');

        return back();
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import_barang.xlsx');
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

        $import = new BarangsImport();
        $import->import($file);

        // dd($import);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        } else {
            // dd($import->failures());
            alert()->success('Success', 'Berhasil menambahkan Barang');
            // $stok = new StoksImport();
            // $stok->import($file);
            // if ($stok->failures()->isNotEmpty()) {
            //     return back()->withFailures($stok->failures());
            // } else {
            //     alert()->success('Success', 'Berhasil menambahkan Barang');
            // }
        }

        return redirect('admin/barang');
    }

    public function import_kode(Request $request)
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

        $import = new UpdateKodesImport();
        $import->import($file);

        // dd($import);

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        } else {
            alert()->success('Success', 'Berhasil mengubah kode Barang');
        }

        return redirect('admin/barang');
    }

    public function generateCode($id)
    {
        $barangs = Barang::where('ruang_id', $id)->withTrashed()->get();
        $barang = Barang::where('ruang_id', $id)->orderByDesc('kode')->withTrashed()->first();
        $ruang = Ruang::where('id', $id)->first();
        if (count($barangs) > 0) {
            $last = substr($barang->kode, 15);
            $jumlah = (int)$last + 1;
            $urutan = sprintf('%03s', $jumlah);
        } else {
            $urutan = "001";
        }

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".01." . $urutan . rand(10, 99);
        return $kode;
    }

    public function normal()
    {
        $barangs = Barang::orderBy('nama', 'ASC')->paginate(10);
        return view('admin.barang.normal', compact('barangs'));
    }

    public function rusak()
    {
        $barangs = Barang::where('rusak', '>', '0')->orderBy('nama', 'ASC')->paginate(10);
        return view('admin.barang.rusak', compact('barangs'));
    }
}
