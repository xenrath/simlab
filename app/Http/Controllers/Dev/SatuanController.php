<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::select(
            'id',
            'nama',
            'singkatan',
            'kali',
            'kategori',
        )->paginate(10);
        
        return view('dev.satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('dev.satuan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'singkatan' => 'required',
            'kali' => 'required',
            'kategori' => 'required',
        ], [
            'nama.required' => 'Nama satuan harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
            'kali.required' => 'Kali harus diisi!',
            'kategori.required' => 'Kategori harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Satuan::create([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'kali' => $request->kali,
            'kategori' => $request->kategori,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Satuan');

        return redirect('dev/satuan');
    }

    public function edit($id)
    {
        $satuan = Satuan::where('id', $id)
            ->select(
                'id',
                'nama',
                'singkatan',
                'kali',
                'kategori',
            )
            ->first();

        return view('dev.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'singkatan' => 'required',
            'kali' => 'required',
            'kategori' => 'required',
        ], [
            'nama.required' => 'Nama satuan harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
            'kali.required' => 'Kali harus diisi!',
            'kategori.required' => 'Kategori harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Satuan::where('id', $id)->update([
            'nama' => $request->nama,
            'singkatan' => $request->singkatan,
            'kali' => $request->kali,
            'kategori' => $request->kategori,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Satuan');

        return redirect('dev/satuan');
    }

    public function destroy($id)
    {
        Satuan::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus Satuan');

        return back();
    }
}
