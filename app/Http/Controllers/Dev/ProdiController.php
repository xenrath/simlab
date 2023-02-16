<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $prodis = Prodi::get();
        return view('dev.prodi.index', compact('prodis'));
    }

    public function create()
    {
        return view('dev.prodi.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:prodis',
            'nama' => 'required',
            'singkatan' => 'required',
        ], [
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama prodi harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Prodi::create($request->all());

        alert()->success('Success', 'Berhasil menambahkan Prodi');

        return redirect('dev/prodi');
    }

    public function edit($id)
    {
        $prodi = Prodi::where('id', $id)->first();

        return view('dev.prodi.edit', compact('prodi'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:prodis',
            'nama' => 'required',
            'singkatan' => 'required',
        ], [
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama prodi harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Prodi::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Prodi');

        return redirect('dev/prodi');
    }

    public function destroy($id)
    {
        $prodi = Prodi::where('id', $id)->first();
        $prodi->delete();

        alert()->success('Success', 'Berhasil menghapus Prodi');

        return back();
    }
}
