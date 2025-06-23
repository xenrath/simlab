<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\PertanyaanKuesioner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KuesionerController extends Controller
{
    public function index()
    {
        $kuesioners = Kuesioner::select('id', 'judul')->get();

        return view('peminjam.kuesioner.index', compact('kuesioners'));
    }

    public function create($id)
    {
        $kuesioner = Kuesioner::where('id', $id)->first();
        $pertanyaan_kuesioners = PertanyaanKuesioner::where('kuesioner_id', $id)->get();

        $is_selesai = JawabanKuesioner::where('peminjam_id', auth()->user()->id)
            ->whereHas('pertanyaankuesioner', function ($query) use ($kuesioner) {
                $query->where('kuesioner_id', $kuesioner->id);
            })
            ->whereYear('created_at', now()->year)
            ->get();

        if (count($is_selesai) > 0) {
            return back();
        }

        return view('peminjam.kuesioner.create', compact('kuesioner', 'pertanyaan_kuesioners'));
    }

    public function store(Request $request, $id)
    {
        $error = array();

        foreach ($request->pertanyaan_id as $key => $pertanyaan_id) {
            $no = $key + 1;
            $validator = Validator::make($request->all(), [
                'jawaban.' . $pertanyaan_id => 'required',
            ], [
                'jawaban.' . $pertanyaan_id . '.required' => 'Pertanyaan nomor ' . $no . ' belum dijawab!',
            ]);

            if ($validator->fails()) {
                array_push($error, $validator->errors()->all()[0]);
            }
        }

        if ($error) {
            return back()->withInput()->with('error', $error);
        }

        foreach ($request->pertanyaan_id as $pertanyaan_id) {
            JawabanKuesioner::create([
                'peminjam_id' => auth()->user()->id,
                'pertanyaankuesioner_id' => $pertanyaan_id,
                'jawaban' => $request->jawaban[$pertanyaan_id]
            ]);
        }

        alert()->success('Success', 'Berhasil mengisi Kuesioner');

        return redirect('peminjam/kuesioner');
    }
}
