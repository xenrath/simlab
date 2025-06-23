<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use App\Models\Tahun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ArsipController extends Controller
{
    public function index()
    {
        $tahuns = Tahun::select('nama')
            ->orderByDesc('nama')
            ->get();
        // 
        return view('kalab.arsip.index', compact('tahuns'));
    }

    // id = tahun
    public function show($id)
    {
        $tahun = $id;
        $arsips = Arsip::where('tahun', $tahun)
            ->select('id', 'nama', 'tahun', 'file')
            ->orderBy('nama')
            ->get();

        return view('kalab.arsip.show', compact('tahun', 'arsips'));
    }

    public function create()
    {
        return view('kalab.arsip.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tahun' => 'required',
            'file' => 'required|mimes:pdf|max:2048',
        ], [
            'nama.required' => 'Nama Arsip tidak boleh kosong!',
            'tahun.required' => 'Tahun tidak boleh kosong!',
            'file.required' => 'File tidak boleh kosong!',
            'file.mimes' => 'File harus berformat .pdf!',
            'file.max' => 'File yang ditambahkan terlalu besar!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', 'Gagal menambahkan Arsip!');
            return back()->withInput()->withErrors($validator->errors());
        }

        $file = Str::slug($request->nama . "_" . $request->tahun, '_') . "." . $request->file->getClientOriginalExtension();
        $file_nama = 'arsip/' . $file;
        $request->file->storeAs('public/uploads/', $file_nama);

        $arsip = Arsip::create([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'file' => $file_nama,
        ]);

        if (!$arsip) {
            alert()->success('Error', 'Gagal menambahkan Arsip!');
            return back();
        }

        alert()->success('Success', 'Berhasil menambahkan Arsip');
        return back();
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tahun' => 'required',
            'file' => 'nullable|mimes:pdf|max:2048',
        ], [
            'nama.required' => 'Nama Arsip tidak boleh kosong!',
            'tahun.required' => 'Tahun tidak boleh kosong!',
            'file.mimes' => 'File harus berformat .pdf!',
            'file.max' => 'File yang ditambahkan terlalu besar!',
        ]);

        if ($validator->fails()) {
            alert()->error('Error', 'Gagal menambahkan Arsip!');
            return back()->withInput()->withErrors($validator->errors())->with('id', $id);
        }

        if ($request->file) {
            Storage::disk('local')->delete('public/uploads/' . Arsip::where('id', $id)->value('file'));
            $file = Str::slug($request->nama . "_" . $request->tahun, '_') . "." . $request->file->getClientOriginalExtension();
            $file_nama = 'arsip/' . $file;
            $request->file->storeAs('public/uploads/', $file_nama);
        } else {
            $file_nama = Arsip::where('id', $id)->value('file');
        }

        $arsip = Arsip::where('id', $id)->update([
            'nama' => $request->nama,
            'tahun' => $request->tahun,
            'file' => $file_nama,
        ]);

        if (!$arsip) {
            alert()->success('Error', 'Gagal memperbarui Arsip!');
            return back();
        }

        alert()->success('Success', 'Berhasil memperbarui Arsip');
        return back();
    }

    public function destroy($id)
    {
        $arsip = Arsip::where('id', $id)->first();

        Storage::disk('local')->delete('public/uploads/' . $arsip->file);
        $arsip->delete();

        alert()->success('Success', 'Berhasil menghapus Arsip');

        return back();
    }
}
