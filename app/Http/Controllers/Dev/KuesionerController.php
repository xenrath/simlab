<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Kuesioner;
use App\Models\PertanyaanKuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KuesionerController extends Controller
{
    public function index()
    {
        $kuesioners = Kuesioner::get();

        return view('dev.kuesioner.index', compact('kuesioners'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
        ], [
            'judul.required' => 'Judul tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kuesioner = Kuesioner::create(request()->all());

        return redirect('dev/kuesioner/' . $kuesioner->id . '/edit');
    }

    public function edit($id)
    {
        $kuesioner = Kuesioner::where('id', $id)->first();
        $pertanyaan_kuesioners = PertanyaanKuesioner::where('kuesioner_id', $kuesioner->id)->get();

        return view('dev.kuesioner.edit', compact('kuesioner', 'pertanyaan_kuesioners'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
        ], [
            'judul.required' => 'Judul tidak boleh kosong!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            alert()->error('Error', $error[0]);
            return back();
        }

        Kuesioner::where('id', $id)->update([
            'judul' => $request->judul
        ]);

        return back();
    }

    public function destroy($id)
    {
        PertanyaanKuesioner::where('kuesioner_id', $id)->delete();
        Kuesioner::where('id', $id)->delete();

        alert()->success('Success', 'Berhasil menghapus Kuesioner');

        return back();
    }
}
