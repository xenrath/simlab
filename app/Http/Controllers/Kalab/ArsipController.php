<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArsipController extends Controller
{
    public function index()
    {
        $arsips = Arsip::get();

        return view('kalab.arsip.index', compact('arsips'));
    }

    public function create()
    {
        return view('kalab.arsip.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'file' => 'required',
        ], [
            'nama.required' => 'Nama arsip tidak boleh kosong!',
            'file.required' => 'File tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $file = str_replace(' ', '', $request->file->getClientOriginalName());
        $namafile = 'arsip/' . date('ymdHis') . '_' . $file;
        $request->file->storeAs('public/uploads/', $namafile);

        Arsip::create([
            'nama' => $request->nama,
            'file' => $namafile
        ]);

        alert()->success('Success', 'Berhasil menambahkan Arsip');

        return redirect('kalab/arsip');
    }
}
