<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\SubProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubProdiController extends Controller
{
    public function index()
    {
        $subprodis = SubProdi::select(
            'id',
            'jenjang',
            'nama'
        )->get();
        return view('dev.subprodi.index', compact('subprodis'));
    }

    public function create()
    {
        $prodis = Prodi::where('is_prodi', true)
            ->select('id', 'singkatan')
            ->get();

        return view('dev.subprodi.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'prodi_id' => 'required',
            'jenjang' => 'required',
            'nama' => 'required',
        ], [
            'prodi_id.required' => 'Main prodi harus dipilih!',
            'jenjang.required' => 'Jenjang harus dipilih!',
            'nama.required' => 'Nama prodi tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        SubProdi::create([
            'prodi_id' => $request->prodi_id,
            'jenjang' => $request->jenjang,
            'nama' => $request->nama
        ]);

        alert()->success('Success', 'Berhasil menambahkan Subprodi');

        return redirect('dev/subprodi');
    }

    public function edit($id)
    {
        $subprodi = SubProdi::where('id', $id)
            ->select('id', 'prodi_id', 'jenjang', 'nama')
            ->first();
        $prodis = Prodi::where('is_prodi', true)
            ->select('id', 'singkatan')
            ->get();

        return view('dev.subprodi.edit', compact('subprodi', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'prodi_id' => 'required',
            'jenjang' => 'required',
            'nama' => 'required',
        ], [
            'prodi_id.required' => 'Main prodi harus dipilih!',
            'jenjang.required' => 'Jenjang harus dipilih!',
            'nama.required' => 'Nama prodi tidak boleh kosong!',
        ]);

        SubProdi::where('id', $id)->update([
            'jenjang' => $request->jenjang,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Subprodi');

        return redirect('dev/subprodi');
    }

    public function destroy($id)
    {
        $subprodi = SubProdi::where('id', $id)->first();
        
        $subprodi->delete();

        alert()->success('Success', 'Berhasil menghapus Subprodi');

        return redirect('dev/subprodi');
    }
}
