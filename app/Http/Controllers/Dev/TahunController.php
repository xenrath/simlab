<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TahunController extends Controller
{
    public function index()
    {
        $tahuns = Tahun::select('id', 'nama')
            ->orderByDesc('nama')
            ->get();
        return view('dev.tahun.index', compact('tahuns'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Nama Tahun harus diisi!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', 'Gagal menambahkan Tahun!');
            return back()->withInput()->withErrors($validator->errors());
        }

        $tahun = Tahun::create([
            'nama' => $request->nama,
        ]);

        if (!$tahun) {
            alert()->error('Error', 'Gagal menambahkan Tahun!');
            return back();
        }

        alert()->success('Success', 'Berhasil menambahkan Tahun');
        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
        ], [
            'nama.required' => 'Tahun harus diisi!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Tahun!');
            return back()->withInput()->withErrors($validator->errors())->with('id', $id);
        }

        $tahun = Tahun::where('id', $id)->update([
            'nama' => $request->nama,
        ]);

        if (!$tahun) {
            alert()->error('Error', 'Gagal memperbarui Tahun!');
            return back();
        }

        alert()->success('Success', 'Berhasil memperbarui Tahun');
        return back();
    }

    public function destroy($id)
    {
        $tahun = Tahun::where('id', $id)->delete();

        if (!$tahun) {
            alert()->error('Error', 'Gagal menghapus Tahun!');
            return back();
        }

        alert()->success('Success', 'Berhasil menghapus Tahun');
        return back();
    }
}
