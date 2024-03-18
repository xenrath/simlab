<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Tempat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $prodis = Prodi::select(
            'id',
            'kode',
            'nama',
            'is_prodi',
            'tempat_id',
        )
            ->with('tempat:id,nama')
            ->get();

        return view('dev.prodi.index', compact('prodis'));
    }

    public function create()
    {
        $tempats = Tempat::select('id', 'nama')->get();

        return view('dev.prodi.create', compact('tempats'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:prodis',
            'nama' => 'required',
            'singkatan' => 'required',
            'is_prodi' => 'required',
            'tempat_id' => 'required',
        ], [
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama prodi harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
            'is_prodi.required' => 'Kategori harus dipilih!',
            'tempat_id.required' => 'Tempat harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Prodi::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'is_prodi' => $request->is_prodi,
            'tempat_id' => $request->tempat_id,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Prodi');

        return redirect('dev/prodi');
    }

    public function show($id)
    {
        $prodi = Prodi::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'singkatan',
                'is_prodi',
                'tempat_id'
            )
            ->with('tempat:id,nama')
            ->first();

        return view('dev.prodi.show', compact('prodi'));
    }

    public function edit($id)
    {
        $prodi = Prodi::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'singkatan',
                'is_prodi',
                'tempat_id'
            )
            ->first();

        $tempats = Tempat::select('id', 'nama')->get();

        return view('dev.prodi.edit', compact('prodi', 'tempats'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:prodis,kode,' . $id . ',id',
            'nama' => 'required',
            'singkatan' => 'required',
            'is_prodi' => 'required',
            'tempat_id' => 'required',
        ], [
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama prodi harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
            'is_prodi.required' => 'Kategori harus dipilih!',
            'tempat_id.required' => 'Tempat harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Prodi::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'is_prodi' => $request->is_prodi,
            'tempat_id' => $request->tempat_id,
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
