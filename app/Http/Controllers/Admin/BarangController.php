<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BarangsImport;
use App\Imports\UpdateKodesImport;
use App\Models\Barang;
use App\Models\Prodi;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $prodi_id = $request->get('prodi_id');

        $barangs = Barang::select('id', 'nama', 'ruang_id')
            ->when($keyword, function ($query, $keyword) {
                $query->where('nama', 'like', "%$keyword%");
            })
            ->when($prodi_id, function ($query, $prodi_id) {
                $query->whereHas('ruang', function ($q) use ($prodi_id) {
                    $q->where('prodi_id', $prodi_id);
                });
            })
            ->with(['ruang' => function ($query) {
                $query->select('id', 'prodi_id')->with('prodi:id,singkatan');
            }])
            ->orderBy('nama')
            ->paginate(10);

        $prodis = Prodi::where('is_prodi', true)->select('id', 'singkatan')->get();

        return view('admin.barang.index', compact('barangs', 'prodis'));
    }

    public function create()
    {
        $ruangs = Ruang::select('id', 'nama', 'prodi_id')
            ->with(['prodi' => function ($query) {
                $query->select('id', 'nama', 'is_prodi')
                    ->where('is_prodi', true);
            }])
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('admin.barang.create', compact('ruangs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required|integer|min:0',
        ], [
            'nama.required' => 'Nama Barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
            'normal.required' => 'Jumlah baik tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', 'Gagal menambahkan Barang!');
            return back()->withInput()->withErrors($validator);
        }

        $kode = now()->format('YmdHis');

        Barang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'total' => (int) $request->normal + (int) ($request->rusak ?? 0),
            'normal' => $request->normal,
            'rusak' => $request->rusak ?? 0,
            'hilang' => $request->hilang ?? 0,
            'satuan_id' => 6,
            'keterangan' => $request->keterangan,
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
        $barang = Barang::with('ruang:id,nama')
            ->select('id', 'kode', 'nama', 'ruang_id', 'normal', 'rusak', 'keterangan', 'gambar')
            ->findOrFail($id);

        $ruangs = Ruang::select('id', 'nama', 'prodi_id')
            ->with(['prodi' => function ($query) {
                $query->select('id', 'nama', 'is_prodi')
                    ->where('is_prodi', true);
            }])
            ->orderBy('nama')
            ->take(10)
            ->get();

        return view('admin.barang.edit', compact('barang', 'ruangs'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:barangs,kode,' . $id,
            'nama' => 'required',
            'ruang_id' => 'required',
            'normal' => 'required|integer|min:0',
            'rusak' => 'nullable|integer|min:0',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama Barang tidak boleh kosong!',
            'ruang_id.required' => 'Ruang Lab harus dipilih!',
            'normal.required' => 'Jumlah baik harus diisi!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Barang!');
            return back()->withInput()->withErrors($validator);
        }

        $rusak = $request->rusak ?? 0;
        $total = (int) $request->normal + (int) $rusak;

        Barang::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'ruang_id' => $request->ruang_id,
            'normal' => $request->normal,
            'rusak' => $rusak,
            'total' => $total,
            'keterangan' => $request->keterangan,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Barang');
        return redirect('admin/barang');
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            alert()->error('Error', 'Barang tidak ditemukan!');
            return back();
        }

        if ($barang->gambar && Storage::exists('public/uploads/' . $barang->gambar)) {
            Storage::delete('public/uploads/' . $barang->gambar);
        }

        $barang->delete();

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
