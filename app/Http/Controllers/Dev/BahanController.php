<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Ruang;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $kategori = $request->get('kategori');
        $keyword = $request->get('keyword');

        if ($keyword != "") {
            $bahans = Bahan::where('nama', 'LIKE', "%$keyword%")->orderBy('nama', 'ASC')->paginate(25);
        } else {
            $bahans = Bahan::orderBy('nama', 'ASC')->paginate(25);
        }

        return view('dev.bahan.index', compact('bahans'));
    }

    public function create()
    {
        $ruangs = Ruang::get();
        $satuans = Satuan::where('kategori', '!=', 'barang')->get();

        return view('dev.bahan.create', compact('ruangs', 'satuans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'ruang_id' => 'required',
            'stok' => 'required',
            'satuan_id' => 'required',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'stok.required' => 'Stok tidak boleh kosong!',
            'satuan_id.required' => 'Satuan harus dipilih!',
            'gambar.required' => 'Gambar harus diisi!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $namagambar = 'bahan/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $namagambar);

        Bahan::create(array_merge($request->all(), [
            'kode' => $this->generateCode($request->ruang_id),
            'total' => $request->normal + $request->rusak,
            'gambar' => $namagambar
        ]));

        alert()->success('Success', 'Berhasil menambahkan Bahan');

        return redirect('dev/bahan');
    }

    public function show($id)
    {
        $bahan = Bahan::find($id);
        return view('dev.bahan.show', compact('bahan'));
    }

    public function edit($id)
    {
        $bahan = Bahan::find($id);
        $ruangs = Ruang::get();

        return view('dev.bahan.edit', compact('bahan', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'ruang_id' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama bahan tidak boleh kosong!',
            'ruang_id.required' => 'Ruang harus dipilih!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        $bahan = Bahan::find($id);

        if ($request->gambar) {
            Storage::disk('local')->delete('public/uploads/' . $request->gambar);
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namagambar = 'barang/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namagambar);
        } else {
            $namagambar = $bahan->gambar;
        }

        Bahan::where('id', $id)->update([
            'kode' => $this->generateCode($request->ruang_id),
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'keterangan' => $request->keterangan,
            'gambar' => $namagambar
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
        $bahans = Bahan::onlyTrashed()->paginate(10);
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
