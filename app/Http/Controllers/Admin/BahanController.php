<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\BahansImport;
use App\Models\Bahan;
use App\Models\Prodi;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $prodi_id = $request->get('prodi_id');

        $bahans = Bahan::select('id', 'kode', 'nama', 'prodi_id')
            ->when($prodi_id, function ($query) use ($prodi_id) {
                $query->where('prodi_id', $prodi_id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%$keyword%");
            })
            ->with('prodi:id,nama')
            ->orderBy('nama')
            ->paginate(10)
            ->appends($request->all());
        $prodis = Prodi::select('id', 'nama')->where('is_prodi', true)->get();

        return view('admin.bahan.index', compact('bahans', 'prodis'));
    }

    public function create()
    {
        $prodis = Prodi::select('id', 'nama')->where('is_prodi', true)->get();
        $satuans = Satuan::select('id', 'nama')
            ->where('kategori', '!=', 'barang')
            ->get();

        return view('admin.bahan.create', compact('prodis', 'satuans'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'prodi_id' => 'required',
            'satuan_pinjam' => 'required',
        ], [
            'nama.required' => 'Nama Bahan tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'satuan_pinjam.required' => 'Satuan Pinjam harus diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal menambahkan Bahan!');
        }

        // $prodi_id = Ruang::where('id', $request->ruang_id)->value('prodi_id');
        $prodi_prefix = Prodi::where('id', $request->prodi_id)->value('kode');
        $kode = $this->generate_kode_bahan($prodi_prefix);
        Bahan::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'satuan_pinjam' => $request->satuan_pinjam,
        ]);

        return redirect('admin/bahan')->with('success', 'Berhasil menambahkan Bahan');
    }

    public function edit($id)
    {
        $bahan = Bahan::select(
            'id',
            'nama',
            'prodi_id',
            'satuan_id',
            'satuan_pinjam',
        )
            ->with('satuan_o:id,nama')
            ->findOrFail($id);
        $prodis = Prodi::select('id', 'nama')->where('is_prodi', true)->get();

        return view('admin.bahan.edit', compact('bahan', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'prodi_id' => 'required',
            'satuan_pinjam' => 'required',
        ], [
            'nama.required' => 'Nama Bahan tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'satuan_pinjam.required' => 'Satuan Pinjam harus diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal menambahkan Bahan!');
        }

        $bahan_prodi_id = Bahan::where('id', $id)->value('prodi_id');
        if ($request->prodi_id != $bahan_prodi_id) {
            $prodi_prefix = Prodi::where('id', $request->prodi_id)->value('kode');
            $kode = $this->generate_kode_bahan($prodi_prefix);
            Bahan::where('id', $id)->update([
                'kode' => $kode,
            ]);
        }

        Bahan::where('id', $id)->update([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'satuan_pinjam' => $request->satuan_pinjam,
        ]);

        return redirect('admin/bahan')->with('success', 'Berhasil memperbarui Bahan');
    }

    public function destroy($id)
    {
        Bahan::where('id', $id)->delete();

        return back()->with('success', 'Berhasil menghapus Bahan');
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import_bahan.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048',
        ], [
            'file.required' => 'File harus ditambahkan!',
            'file.mimes' => 'Format file harus Excel (xlsx, xls) atau CSV!',
            'file.max' => 'Ukuran file maksimal 2MB!',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Gagal mengimpor Bahan!')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('file');
            $import = new BahansImport();
            $import->import($file);

            return redirect()->back()->with('success', 'Berhasil menambahkan Bahan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat impor: ' . $e->getMessage());
        }
    }

    public function cetak(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'jumlah' => 'required|numeric|min:1',
        ], [
            'jumlah.required' => 'Jumlah harus diisi!',
            'jumlah.numeric' => 'Jumlah harus berupa angka!',
            'jumlah.min' => 'Jumlah minimal 1!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('id', $id)
                ->with('error', 'Gagal mencetak Barcode!');
        }

        $jumlah = $request->jumlah;
        $bahan = Bahan::where('id', $id)
            ->select('kode', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->first();

        $bahans = Bahan::select('kode', 'nama', 'prodi_id')
            ->with('prodi:id,nama')
            ->orderBy('nama')
            ->take(24)
            ->get();

        $pdf = Pdf::loadview('admin.bahan.cetak', compact('bahan', 'jumlah', 'bahans'));
        return $pdf->stream('barcode.pdf');
    }

    public function kode_perbarui($id)
    {
        $bahan = Bahan::where('id', $id)->first();
        if (!$bahan->prodi_id) {
            return back()->with('error', 'Lakukan pembaruan bahan terlebih dahulu!');
        }
        $prodi_prefix = Prodi::where('id', $bahan->prodi_id)->value('kode');
        $kode = $this->generate_kode_bahan($prodi_prefix);

        Bahan::where('id', $id)->update([
            'kode' => $kode,
        ]);

        return back()->with('success', 'Berhasil memperbarui Kode Bahan');
    }

    public function generate_kode_bahan($prodi_prefix)
    {
        do {
            $random = rand(10000000, 99999999); // 8 digit random
            $kode = strtoupper($prodi_prefix) . '-' . $random;
        } while (Bahan::where('kode', $kode)->exists());

        return $kode;
    }
}
