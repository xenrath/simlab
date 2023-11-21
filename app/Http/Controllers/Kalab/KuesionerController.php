<?php

namespace App\Http\Controllers\Kalab;

use App\Exports\KuesionerExport;
use App\Http\Controllers\Controller;
use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\PertanyaanKuesioner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function show($id)
    {
        $kuesioner = Kuesioner::where('id', $id)->select('id', 'judul', 'created_at')->first();
        $peminjams = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })
            ->select('peminjam_id')
            ->groupBy('peminjam_id')
            ->get();

        $data = array();

        foreach ($peminjams as $peminjam) {
            $user = User::where('id', $peminjam->peminjam_id)->select('id', 'nama', 'subprodi_id')->with('subprodi:id,jenjang,nama')->first();

            array_push($data, array(
                'id' => $user->id,
                'nama' => $user->nama,
                'prodi' => $user->subprodi->jenjang . ' ' . $user->subprodi->nama
            ));
        }

        return view('kalab.kuesioner.show', compact('kuesioner', 'data'));
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

    public function grafik($id)
    {
        $kuesioner = Kuesioner::where('id', $id)->select('id', 'singkatan')->first();

        $pertanyaan_kuesioners = PertanyaanKuesioner::where('kuesioner_id', $id)->select('id', 'pertanyaan')->get();

        $grafiks = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })
            ->select('pertanyaankuesioner_id', 'jawaban')
            ->get()
            ->groupBy('pertanyaankuesioner_id');

        $data = array();
        $keterangan = array(
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Puas',
            4 => 'Sangat Puas'
        );

        foreach ($grafiks as $key => $grafik) {
            $pertanyaan = PertanyaanKuesioner::where('id', $key)->value('pertanyaan');
            $jawaban = array_map('intval', json_decode($grafik->pluck('jawaban')));
            $jumlah = array_count_values($jawaban);

            for ($i = 1; $i <= 4; $i++) {
                $data[$key]['label'][] = $keterangan[$i];
                $data[$key]['data'][] = array_count_values($jawaban)[$i] ?? 0;
            }
        }

        return view('kalab.kuesioner.grafik', compact('kuesioner', 'pertanyaan_kuesioners', 'data'));
    }
}
