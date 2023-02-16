<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BahansImport;
use App\Imports\StoksImport;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
use App\Models\StokBahan;
use App\Models\Tempat;
use Carbon\Carbon;
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
            $bahans = Bahan::whereHas('ruang', function ($query) use ($tempat) {
                $query->where('tempat_id', $tempat);
            })->where('nama', 'LIKE', "%$keyword%")->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($tempat != "" && $keyword == "") {
            $bahans = Bahan::whereHas('ruang', function ($query) use ($tempat) {
                $query->where('tempat_id', $tempat);
            })->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($tempat == "" && $keyword != "") {
            $bahans = Bahan::where('nama', 'LIKE', "%$keyword%")->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $bahans = Bahan::orderBy('nama', 'ASC')->paginate(10);
        }

        // if ($keyword != "") {
        //     $bahans = Barang::where([
        //         ['kategori', 'bahan'],
        //         ['nama', 'LIKE', "%$keyword%"],
        //     ])->orderBy('nama', 'ASC')->paginate(10);
        // } else {
        //     $bahans = Barang::where('kategori', 'bahan')->orderBy('nama', 'ASC')->paginate(10);
        // }

        // if (auth()->user()->tempat->id == '1') {
        //     $bahans = $data->where('tempat', 'lab')->paginate(10);
        // } else {
        //     $bahans = $data->where('tempat', 'farmasi')->paginate(10);
        // }

        $tempats = Tempat::get();

        return view('admin.bahan.index', compact('bahans', 'tempats'));
    }

    public function create()
    {
        $tempats = Tempat::get();
        $satuans = Satuan::where('kategori', '!=', 'barang')->get();
        $ruangs = Ruang::get();

        return view('admin.bahan.create', compact('tempats', 'satuans', 'ruangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'ruang_id' => 'required',
            'stok' => 'required',
            'satuan_id' => 'required',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama bahan tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'stok.required' => 'Stok barang harus diisi!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'gambar.required' => 'Gambar harus ditambahkan!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $namagambar = 'bahan/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $namagambar);

        $bahan = Bahan::create(array_merge($request->all(), [
            'kode' => $this->generateCode($request->ruang_id),
            'gambar' => $namagambar
        ]));

        if ($bahan) {
            StokBahan::create(array_merge([
                'bahan_id' => $bahan->id,
                'jumlah' => $request->stok,
                'satuan_id' => $request->satuan_id,
            ]));
            alert()->success('Success', 'Berhasil menambahkan Bahan');
        } else {
            alert()->error('Error', 'Gagal menambahkan Bahan!');
        }

        return redirect('admin/bahan');
    }

    public function show($id)
    {
        $bahan = Bahan::find($id);
        return view('admin.bahan.show', compact('bahan'));
    }

    public function edit($id)
    {
        $bahan = Bahan::find($id);
        $prodis = Prodi::get();
        $ruangs = Ruang::get();

        return view('admin.bahan.edit', compact('bahan', 'prodis', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $bahan = Bahan::find($id);

        if ($bahan->gambar) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'ruang_id' => 'required',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ], [
                'nama.required' => 'Nama bahan tidak boleh kosong!',
                'ruang_id.required' => 'Ruang harus dipilih!',
                'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'ruang_id' => 'required',
                'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ], [
                'nama.required' => 'Nama bahan tidak boleh kosong !',
                'ruang_id.required' => 'Ruang harus dipilih !',
                'gambar.required' => 'Gambar harus ditambahkan !',
                'gambar.image' => 'Gambar harus berformat jpeg, jpg, png !',
            ]);
        }

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $bahan->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namagambar = 'bahan/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namagambar);
        } else {
            $namagambar = $bahan->gambar;
        }

        if ($request->ruang_id != $bahan->ruang_id) {
            $kode = $this->generateCode($request->ruang_id);
        } else {
            $kode = $bahan->kode;
        }

        $update = $bahan->update([
            'kode' => $kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'keterangan' => $request->keterangan,
            'gambar' => $namagambar
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui Bahan');
        } else {
            alert()->error('Error', 'Gagal menambahkan Bahan!');
        }

        return redirect('admin/bahan');
    }

    public function destroy($id)
    {
        $bahan = Bahan::find($id);
        $delete = $bahan->delete();

        if ($delete) {
            Storage::disk('local')->delete('public/uploads/' . $bahan->foto);
            StokBahan::where('bahan_id', $bahan->id)->delete();
            alert()->success('Success', 'Berhasil menghapus bahan');
        } else {
            alert()->error('Error', 'Gagal menghapus bahan!');
        }

        return redirect()->back();
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

    public function generateCode($id)
    {
        $bahans = Bahan::where('ruang_id', $id)->withTrashed()->get();
        $bahan = Bahan::where('ruang_id', $id)->orderByDesc('kode')->withTrashed()->first();
        $ruang = Ruang::where('id', $id)->first();
        if (count($bahans) > 0) {
            $last = substr($bahan->kode, 15);
            $jumlah = (int)$last + 1;
            $urutan = sprintf('%03s', $jumlah);
        } else {
            $urutan = "001";
        }

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".02." . $urutan;
        return $kode;
    }
}
