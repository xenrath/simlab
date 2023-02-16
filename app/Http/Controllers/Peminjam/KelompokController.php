<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Kelompok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelompokController extends Controller
{
    public function index()
    {
        $kelompoks = Kelompok::where('laboran_id', auth()->user()->id)->paginate(10);
        return view('laboran.kelompok.index', compact('kelompoks'));
    }

    public function create()
    {
        return view('laboran.kelompok.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kelompok' => 'required',
            'ketua_kelompok' => 'required',
            'anggota_kelompok' => 'required',
            'shift' => 'required',
            'jam' => 'required'
        ], [
            'nama_kelompok.required' => 'Nama kelompok tidak boleh kosong!',
            'ketua_kelompok.required' => 'Ketua kelompok tidak boleh kosong!',
            'anggota_kelompok.required' => 'Anggota kelompok tidak boleh kosong!',
            'shift.required' => 'Shift harus dipilih!',
            'jam.required' => 'Jam harus diisi!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('kelompok', $error);
        }

        // return response($request->anggota_kelompok);

        Kelompok::create(array_merge([
            'pinjam_id' => $request->pinjam_id,
            'nama' => $request->nama_kelompok,
            'ketua' => $request->ketua_kelompok,
            'anggota' => $request->anggota_kelompok,
            'shift' => $request->shift,
            'jam' => $request->jam,
        ]));

        // return json_encode($kelompok);

        return back();
    }

    public function destroy($id)
    {
        $kelompok = Kelompok::where('id', $id)->first();
        $kelompok->delete();

        $count = Kelompok::where('pinjam_id', $kelompok->pinjam_id)->count();

        return $count;
    }
}
