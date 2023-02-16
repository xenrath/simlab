<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuans = Satuan::paginate(10);
        return view('dev.satuan.index', compact('satuans'));
    }

    public function create()
    {
        return view('dev.satuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'singkatan' => 'required',
            'kali' => 'required',
        ], [
            'nama.required' => 'Nama satuan harus diisi!',
            'singkatan.required' => 'Singkatan harus diisi!',
            'kali.required' => 'Kali harus diisi!',
        ]);

        Satuan::create($request->all());

        alert()->success('Success', 'Berhasil menambahkan satuan');

        return redirect('dev/satuan');
    }

    public function destroy($id)
    {
        $satuan = Satuan::where('id', $id)->first();
        $satuan->delete();

        alert()->success('Success', 'Berhasil menghapus satuan');

        return back();
    }
}
