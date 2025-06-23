<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tamu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $tamus = Tamu::select('id', 'nama', 'telp', 'institusi', 'alamat')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%$keyword%")->orWhere('institusi', 'like', "%$keyword%");
                });
            })
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.tamu.index', compact('tamus'));
    }

    public function create()
    {
        return view('admin.tamu.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'institusi' => 'required',
            'telp' => 'required|unique:tamus',
        ], [
            'nama.required' => 'Nama Tamu tidak boleh kosong!',
            'institusi.required' => 'Asal Institusi tidak boleh kosong!',
            'telp.required' => 'Nomor Telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
        ]);

        Tamu::create([
            'nama' => $validated['nama'],
            'institusi' => $validated['institusi'],
            'telp' => $validated['telp'],
            'alamat' => $request->alamat, // boleh langsung karena tidak divalidasi
        ]);

        alert()->success('Success', 'Berhasil menambahkan Tamu');
        return redirect('admin/tamu');
    }

    public function show($id)
    {
        $tamu = Tamu::where('id', $id)
            ->select(
                'id',
                'nama',
                'institusi',
                'telp',
                'alamat'
            )
            ->first();

        return view('admin.tamu.show', compact('tamu'));
    }

    public function edit($id)
    {
        $tamu = Tamu::select(
            'id',
            'nama',
            'institusi',
            'telp',
            'alamat'
        )->findOrFail($id);

        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required',
            'institusi' => 'required',
            'telp' => 'required|unique:tamus,telp,' . $id,
        ], [
            'nama.required' => 'Nama Tamu tidak boleh kosong!',
            'institusi.required' => 'Asal Institusi tidak boleh kosong!',
            'telp.required' => 'Nomor Telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
        ]);

        Tamu::where('id', $id)->update([
            'nama' => $validated['nama'],
            'institusi' => $validated['institusi'],
            'telp' => $validated['telp'],
            'alamat' => $request->alamat,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Tamu');
        return redirect('admin/tamu');
    }

    public function destroy($id)
    {
        $tamu = Tamu::findOrFail($id);
        $tamu->delete();

        alert()->success('Success', 'Berhasil menghapus Tamu');
        return back();
    }
}
