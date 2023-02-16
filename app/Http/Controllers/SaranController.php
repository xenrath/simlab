<?php

namespace App\Http\Controllers;

use App\Models\Saran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SaranController extends Controller
{
    public function index()
    {
        $sarans = Saran::get();

        return view('saran.index', compact('sarans'));
    }

    public function create()
    {
        return view('saran.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'saran' => 'required',
            'kategori' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'saran.required' => 'Saran tidak boleh kosong!',
            'kategori.required' => 'Saran tidak boleh kosong!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->gambar) {
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namagambar = 'barang/' . date('mYdHs') . rand(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namagambar);
        } else {
            $namagambar = "";
        }

        $create = Saran::create(array_merge($request->all(), [
            'gambar' => $namagambar
        ]));

        if ($create) {
            alert()->success('Success', 'Berhasil menambahkan Saran');
        } else {
            alert()->error('Error', 'Gagal menambahkan Saran!');
        }

        return redirect('saran/create');
    }

    public function show($id)
    {
        return view('saran.show');
    }

    public function edit($id)
    {
    }

    public function update($id)
    {
    }

    public function destroy($id)
    {
    }
}
