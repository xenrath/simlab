<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Praktik;
use Illuminate\Http\Request;

class PraktikController extends Controller
{
    public function index()
    {
        $praktiks = Praktik::get();
        return view('dev.praktik.index', compact('praktiks'));
    }

    public function create()
    {
        return view('dev.praktik.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama praktik harus diisi!',
        ]);

        Praktik::create($request->all());

        alert()->success('Success', 'Berhasil menambahkan Praktik');

        return redirect('dev/praktik');
    }

    public function destroy($id)
    {
        $praktik = Praktik::where('id', $id)->first();
        $praktik->delete();

        alert()->success('Success', 'Berhasil menghapus Praktik');

        return back();
    }
}
