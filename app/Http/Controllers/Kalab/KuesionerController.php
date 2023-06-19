<?php

namespace App\Http\Controllers\Kalab;

use App\Exports\KuesionerExport;
use App\Http\Controllers\Controller;
use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\PertanyaanKuesioner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class KuesionerController extends Controller
{
    public function index()
    {
        $kuesioners = Kuesioner::get();
        $collect = collect();

        foreach ($kuesioners as $kuesioner) {
            $jawabans = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) {
                $query->where('kuesioner_id', '2');
            })->selectRaw('year(created_at) tahun')->groupBy('tahun')->orderBy('tahun', 'desc')->get();

            foreach ($jawabans as $jawaban) {
                $collect->push(['kuesioner_id' => $kuesioner->id, 'judul' => $kuesioner->judul, 'tahun' => $jawaban->tahun]);
            }
        }

        $data = $collect->sortByDesc('tahun')->values()->all();

        return view('kalab.kuesioner.index', compact('data'));
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

        return redirect('kalab/kuesioner/' . $kuesioner->id . '/edit');
    }

    public function edit($id)
    {
        $kuesioner = Kuesioner::where('id', $id)->first();
        $pertanyaan_kuesioners = PertanyaanKuesioner::where('kuesioner_id', $kuesioner->id)->get();

        return view('kalab.kuesioner.edit', compact('kuesioner', 'pertanyaan_kuesioners'));
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

    public function download($id, $tahun)
    {
        $kuesioner = Kuesioner::where('id', $id)->first();

        $nama = str_slug($kuesioner->judul) . "-" . $tahun . ".xlsx";

        return Excel::download(new KuesionerExport($id, $tahun), $nama);   
    }
}
