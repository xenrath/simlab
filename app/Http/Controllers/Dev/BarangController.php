<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Satuan;
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
            $barangs = Barang::where('nama', 'LIKE', "%$keyword%")->orderBy('kode', 'ASC')->paginate(25);
        } else {
            $barangs = Barang::orderBy('kode')->paginate(25);
        }

        return view('dev.barang.index', compact('barangs'));
    }

    public function create()
    {
        $ruangs = Ruang::get();
        $satuans = Satuan::get();

        return view('dev.barang.create', compact('ruangs', 'satuans'));
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
                'jumlah' => $total,
                'satuan_id' => $request->satuan_id,
            ]));
            alert()->success('Success', 'Berhasil menambahkan Barang');
        } else {
            alert()->error('Error', 'Gagal menambahkan Barang!');
        }

        return redirect('dev/barang');
    }

    public function show($id)
    {
        $barang = Barang::withTrashed()->where('id', $id)->first();
        // return response($barang);
        return view('dev.barang.show', compact('barang'));
    }

    public function edit($id)
    {
        $barang = Barang::find($id);
        $prodis = Prodi::get();
        $ruangs = Ruang::get();

        return view('dev.barang.edit', compact('barang', 'prodis', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::find($id);

        if ($barang->gambar) {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'ruang_id' => 'required',
                'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
            ], [
                'nama.required' => 'Nama barang tidak boleh kosong!',
                'ruang_id.required' => 'Ruang harus dipilih!',
                'gambar.image' => 'Gambar harus berformat jpeg, jpg, png !',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'nama' => 'required',
                'ruang_id' => 'required',
                'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ], [
                'nama.required' => 'Nama Barang tidak boleh kosong !',
                'ruang_id.required' => 'Ruang harus dipilih !',
                'gambar.required' => 'Gambar harus diisi !',
                'gambar.image' => 'Gambar harus berformat jpeg, jpg, png !',
            ]);
        }

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

        if ($request->ruang_id != $barang->ruang_id) {
            $kode = $this->generateCode($request->ruang_id);
        } else {
            $kode = $barang->kode;
        }

        Barang::where('id', $id)->update([
            'kode' => $kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'gambar' => $namagambar
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
            $barang = Barang::where('id', $id)->first();
            Barang::where('id', $id)->onlyTrashed()->forceDelete();
            Storage::disk('local')->delete('public/uploads/' . $barang->gambar);
        } else {
            $barangs = Barang::onlyTrashed();
            $barangs->forceDelete();
            foreach ($barangs as $barang) {
                Storage::disk('local')->delete('public/uploads/' . $barang->gambar);
            }
        }

        alert()->success('Success', 'Berhasil menghapus Barang');

        return back();
    }

    public function satuan(Request $request)
    {
        $kategori = $request->kategori;

        if ($kategori == 'barang') {
            $satuans = Satuan::where('kategori', 'barang')->get();
        } else {
            $satuans = Satuan::where('kategori', '!=', 'barang')->get();
        }

        return json_encode($satuans);
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
}
