<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Tempat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TempatController extends Controller
{
    public function index()
    {
        $tempats = Tempat::get();

        return view('dev.tempat.index', compact('tempats'));
    }

    public function create()
    {
        return view('dev.tempat.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
        ], [
            'kode.required' => 'Kode harus diisi!',
            'nama.required' => 'Nama tempat harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Tempat::create($request->all());

        alert()->success('Success', 'Berhasil menambahkan Tempat');

        return redirect('dev/tempat');
    }

    public function edit($id)
    {
        $tempat = Tempat::where('id', $id)->first();

        return view('dev.tempat.edit', compact('tempat'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required',
        ], [
            'kode.required' => 'Kode harus diisi!',
            'nama.required' => 'Nama tempat harus diisi!', 
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Tempat::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Tempat');

        return redirect('dev/tempat');
    }

    public function destroy($id)
    {
        $tempat = Tempat::where('id', $id)->first();
        $tempat->delete();

        alert()->success('Success', 'Berhasil menghapus Tempat');

        return back();
    }
}
