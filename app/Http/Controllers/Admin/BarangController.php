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
        $tempat = $request->get('tempat');

        if ($tempat != "" && $keyword != "") {
            $barangs = Barang::whereHas('ruang', function ($query) use ($tempat) {
                $query->where('tempat_id', $tempat);
            })->where('nama', 'LIKE', "%$keyword%")->orderBy('ruang_id', 'ASC')->paginate(10);
        } elseif ($tempat != "" && $keyword == "") {
            $barangs = Barang::whereHas('ruang', function ($query) use ($tempat) {
                $query->where('tempat_id', $tempat);
            })->orderBy('ruang_id', 'ASC')->paginate(10);
        } elseif ($tempat == "" && $keyword != "") {
            $barangs = Barang::where('nama', 'LIKE', "%$keyword%")->orderBy('ruang_id', 'ASC')->paginate(10);
        } else {
            $barangs = Barang::orderBy('ruang_id', 'ASC')->paginate(10);
        }

        // if (auth()->user()->tempat->id == '1') {
        //     $barangs = $data->where('tempat', 'lab')->paginate(10);
        // } else {
        //     $barangs = $data->where('tempat', 'farmasi')->paginate(10);
        // }

        $tempats = Tempat::get();

        return view('admin.barang.index', compact('barangs', 'tempats'));
    }

    public function create()
    {
        $ruangs = Ruang::get();
        $satuans = Satuan::where('kategori', 'barang')->get();

        return view('admin.barang.create', compact('ruangs', 'satuans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required',
            'rusak' => 'required',
            'satuan_id' => 'required',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'normal.required' => 'Jumlah baik tidak boleh kosong!',
            'rusak.required' => 'Jumlah rusak tidak boleh kosong!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'gambar.required' => 'Gambar harus ditambahkan!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $namagambar = 'barang/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $namagambar);
        $total = $request->normal + $request->rusak;

        $barang = Barang::create(array_merge($request->all(), [
            'kode' => $this->generateCode($request->ruang_id),
            'total' => $total,
            'gambar' => $namagambar
        ]));

        if ($barang) {
            StokBarang::create(array_merge([
                'barang_id' => $barang->id,
                'normal' => $request->normal,
                'rusak' => $request->rusak,
                'satuan_id' => $request->satuan_id,
            ]));
            alert()->success('Success', 'Berhasil menambahkan Barang');
        } else {
            alert()->error('Error', 'Gagal menambahkan Barang!');
        }

        return redirect('admin/barang');
    }

    public function show($id)
    {
        $barang = Barang::find($id);
        return view('admin.barang.show', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        $prodis = Prodi::get();
        $ruangs = Ruang::get();

        return view('admin.barang.edit', compact('barang', 'prodis', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        // if ($barang->gambar) {
        //     $validator = Validator::make($request->all(), [
        //         'nama' => 'required',
        //         'ruang_id' => 'required',
        //         'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        //     ], [
        //         'nama.required' => 'Nama barang tidak boleh kosong!',
        //         'ruang_id.required' => 'Ruang harus dipilih!',
        //         'gambar.image' => 'Gambar harus berformat jpeg, jpg, png !',
        //     ]);
        // } else {
        //     $validator = Validator::make($request->all(), [
        //         'nama' => 'required',
        //         'ruang_id' => 'required',
        //         // 'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        //         'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        //     ], [
        //         'nama.required' => 'Nama Barang tidak boleh kosong !',
        //         'ruang_id.required' => 'Ruang harus dipilih !',
        //         // 'gambar.required' => 'Gambar harus diisi !',
        //         'gambar.image' => 'Gambar harus berformat jpeg, jpg, png !',
        //     ]);
        // }

        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:barangs,kode,' . $id,
            'nama' => 'required',
            'ruang_id' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'Kode barang tidak boleh kosong!',
            'kode.unique' => 'Kode barang sudah digunakan!',
            'nama.required' => 'Nama barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png !',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $request->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namagambar = 'barang/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namagambar);
        } else {
            $namagambar = $barang->gambar;
        }

        Barang::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'gambar' => $namagambar
        ]);

        alert()->success('Success', 'Berhasil memperbarui Barang');

        return redirect('admin/barang');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        try {
            $barang->delete();
            Storage::disk('local')->delete('public/uploads/' . $barang->foto);
            StokBarang::where('barang_id', $barang->id)->delete();
        } catch (\Throwable $th) {
            return back()->with('error', 'Laboran masih memiliki tanggung jawab pada ruang lab !');
        }

        alert()->success('Success', 'Berhasil menghapus Barang');

        return redirect()->back();
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

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".01." . $urutan;
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
