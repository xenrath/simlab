<?php

namespace App\Http\Controllers\Kalab;

use App\Exports\KuesionerExport;
use App\Http\Controllers\Controller;
use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\PertanyaanKuesioner;
use App\Models\SubProdi;
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

    public function download($id, $tahun)
    {
        $kuesioner = Kuesioner::where('id', $id)->first();

        $nama = str_slug($kuesioner->judul) . "-" . $tahun . ".xlsx";

        return Excel::download(new KuesionerExport($id, $tahun), $nama);
    }

    public function grafik($id)
    {
        $urutan = Kuesioner::where('id', $id)->value('urutan');

        if ($urutan == 'pertanyaan') {
            return $this->grafik_pertanyaan($id);
        } else if ($urutan == 'prodi') {
            return $this->grafik_prodi($id);
        }
    }

    public function grafik_pertanyaan($id)
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
            $jawaban = array_map('intval', json_decode($grafik->pluck('jawaban')));
            $jumlah = array_count_values($jawaban);

            for ($i = 1; $i <= 4; $i++) {
                $data[$key]['label'][] = $keterangan[$i];
                $data[$key]['data'][] = array_count_values($jawaban)[$i] ?? 0;
            }
        }

        return view('kalab.kuesioner.grafik_pertanyaan', compact('kuesioner', 'pertanyaan_kuesioners', 'data'));
    }

    public function grafik_prodi($id)
    {
        $kuesioner = Kuesioner::where('id', $id)->select('id', 'singkatan')->first();

        $grafiks = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })
            ->select('peminjam_id', 'pertanyaankuesioner_id', 'jawaban')
            ->with('peminjam:id,subprodi_id')
            ->get()
            ->groupBy('peminjam.subprodi_id');

        $data = array();
        $keterangan = array(
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Puas',
            4 => 'Sangat Puas'
        );

        foreach ($grafiks as $key => $grafik) {
            $subprodi = SubProdi::where('id', $key)->select('jenjang', 'nama')->first();
            $jawaban = array_map('intval', json_decode($grafik->pluck('jawaban')));
            $jumlah = array_count_values($jawaban);

            for ($i = 1; $i <= 4; $i++) {
                $data[$key]['label'][] = $keterangan[$i];
                $data[$key]['data'][] = array_count_values($jawaban)[$i] ?? 0;
            }
        }

        $subprodis = SubProdi::whereIn('id', array_keys($data))->select('id', 'jenjang', 'nama')->get();

        return view('kalab.kuesioner.grafik_prodi', compact('kuesioner', 'subprodis', 'data'));
    }
}
