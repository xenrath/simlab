<?php

namespace App\Http\Controllers\Laboran\Bidan;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class BahanController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $bahans = Bahan::where('prodi_id', auth()->user()->prodi_id)
            ->select('id', 'kode', 'nama', 'prodi_id')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%$keyword%");
            })
            ->with('prodi:id,nama')
            ->orderBy('nama')
            ->paginate(10)
            ->appends($request->all());

        return view('laboran.bidan.bahan.index', compact('bahans'));
    }

    public function create()
    {
        $prodi = Prodi::where('id', auth()->user()->prodi_id)
            ->select('id', 'nama')
            ->first();

        return view('laboran.bidan.bahan.create', compact('prodi'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'satuan_pinjam' => 'required',
        ], [
            'nama.required' => 'Nama Bahan tidak boleh kosong!',
            'satuan_pinjam.required' => 'Satuan Pinjam harus diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal menambahkan Bahan!');
        }

        $prodi_id = auth()->user()->prodi_id;
        $prodi_prefix = Prodi::where('id', $prodi_id)->value('kode');
        $kode = $this->generate_kode_bahan($prodi_prefix);
        Bahan::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'prodi_id' => $prodi_id,
            'satuan_pinjam' => $request->satuan_pinjam,
        ]);

        return redirect('laboran/bidan/bahan')->with('success', 'Berhasil menambahkan Bahan');
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
        $prodi = Prodi::where('id', auth()->user()->prodi_id)
            ->select('id', 'nama')
            ->first();

        return view('laboran.bidan.bahan.edit', compact('bahan', 'prodi'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'satuan_pinjam' => 'required',
        ], [
            'nama.required' => 'Nama Bahan tidak boleh kosong!',
            'satuan_pinjam.required' => 'Satuan Pinjam harus diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal menambahkan Bahan!');
        }

        Bahan::where('id', $id)->update([
            'nama' => $request->nama,
            'satuan_pinjam' => $request->satuan_pinjam,
        ]);

        return redirect('laboran/bidan/bahan')->with('success', 'Berhasil memperbarui Bahan');
    }

    public function destroy($id)
    {
        Bahan::where('id', $id)->delete();

        return back()->with('success', 'Berhasil menghapus Bahan');
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

        $pdf = Pdf::loadview('laboran.bidan.bahan.cetak', compact('bahan', 'jumlah'));
        return $pdf->stream('barcode.pdf');
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
