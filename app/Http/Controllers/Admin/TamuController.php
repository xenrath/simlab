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

        if ($keyword != "") {
            $tamus = Tamu::where('nama', 'like', "%$keyword%")
                ->select('id', 'nama', 'institusi')
                ->orderBy('nama')
                ->paginate(10);
        } else {
            $tamus = Tamu::select('id', 'nama', 'institusi')
                ->orderBy('nama')
                ->paginate(10);
        }


        return view('admin.tamu.index', compact('tamus'));
    }

    public function create()
    {
        return view('admin.tamu.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'institusi' => 'required',
            'telp' => 'required|unique:tamus',
        ], [
            'nama.required' => 'Nama Tamu tidak boleh kosong!',
            'institusi.required' => 'Asal Institusi tidak boleh kosong!',
            'telp.required' => 'Nomor Telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Tamu::create([
            'nama' => $request->nama,
            'institusi' => $request->institusi,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
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
        $tamu = Tamu::where('id', $id)
            ->select(
                'id',
                'nama',
                'institusi',
                'telp',
                'alamat'
            )
            ->first();

        return view('admin.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'institusi' => 'required',
            'telp' => 'required|unique:tamus,telp,' . $id . ',id',
        ], [
            'nama.required' => 'Nama Tamu tidak boleh kosong!',
            'institusi.required' => 'Asal Institusi tidak boleh kosong!',
            'telp.required' => 'Nomor Telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Tamu::where('id', $id)->update([
            'nama' => $request->nama,
            'institusi' => $request->institusi,
            'telp' => $request->telp,
            'alamat' => $request->alamat
        ]);

        alert()->success('Success', 'Berhasil memperbarui Tamu');
        
        return redirect('admin/tamu');
    }

    public function destroy($id)
    {
        Tamu::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus Tamu');

        return back();
    }
}
