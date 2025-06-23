<?php

namespace App\Http\Controllers\Kalab;

use App\Exports\KuesionerExport;
use App\Http\Controllers\Controller;
use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\PertanyaanKuesioner;
use App\Models\SubProdi;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class KuesionerController extends Controller
{
    public function index()
    {
        $kuesioners = Kuesioner::get();
        $collect = collect();
        // 
        $jawabans = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) {
            $query->where('kuesioner_id', 2);
        })
            ->selectRaw('YEAR(created_at) as tahun')
            ->groupBy('tahun')
            ->orderByDesc('tahun')
            ->pluck('tahun');
        // 
        foreach ($kuesioners as $kuesioner) {
            foreach ($jawabans as $tahun) {
                $collect->push([
                    'kuesioner_id' => $kuesioner->id,
                    'judul' => $kuesioner->judul,
                    'tahun' => $tahun
                ]);
            }
        }
        // 
        $data = $collect->sortByDesc('tahun')->values()->all();
        // 
        return view('kalab.kuesioner.index', compact('data'));
    }

    public function show(Request $request, $id, $tahun)
    {
        $page = $request->page ?? '10';
        // 
        $kuesioner = Kuesioner::where('id', $id)->select('id', 'judul')->first();
        // 
        $peminjamIds = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })
            ->when(!empty($tahun), function ($query) use ($tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->orderBy('created_at', 'desc')
            ->pluck('peminjam_id')
            ->unique()
            ->values();
        // 
        $total = $peminjamIds->count();
        $users = User::whereIn('id', $peminjamIds->take($page))
            ->select('id', 'nama', 'subprodi_id')
            ->with('subprodi:id,jenjang,nama')
            ->get();
        // 
        $data = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'nama' => $user->nama,
                'prodi' => $user->subprodi?->jenjang . ' ' . $user->subprodi?->nama,
            ];
        })->toArray();
        // 
        return view('kalab.kuesioner.show', compact('kuesioner', 'tahun', 'total', 'data'));
    }

    public function download($id, $tahun)
    {
        $kuesioner = Kuesioner::where('id', $id)->first();

        $nama = str_slug($kuesioner->judul) . "-" . $tahun . ".xlsx";

        return Excel::download(new KuesionerExport($id, $tahun), $nama);
    }

    public function grafik($id, $tahun)
    {
        $urutan = Kuesioner::where('id', $id)->value('urutan');

        if ($urutan == 'pertanyaan') {
            return $this->grafik_pertanyaan($id, $tahun);
        } else if ($urutan == 'prodi') {
            return $this->grafik_prodi($id, $tahun);
        }
    }

    public function grafik_pertanyaan($id, $tahun)
    {
        $kuesioner = Kuesioner::select('id', 'singkatan', 'judul')->findOrFail($id);
        // 
        $pertanyaan_kuesioners = PertanyaanKuesioner::where('kuesioner_id', $id)
            ->select('id', 'pertanyaan')
            ->get();
        // 
        $keterangan = [
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Puas',
            4 => 'Sangat Puas'
        ];
        // 
        $jawabanGrouped = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })
            ->select('pertanyaankuesioner_id', 'jawaban')
            ->get()
            ->groupBy('pertanyaankuesioner_id');
        // 
        $data = [];
        // 
        foreach ($jawabanGrouped as $pertanyaanId => $jawabans) {
            $flattenedJawaban = $jawabans->pluck('jawaban')
                ->map(function ($value) {
                    return (int) $value;
                })
                ->toArray();

            $counts = array_count_values($flattenedJawaban);
            // 
            foreach (range(1, 4) as $i) {
                $jumlah = $counts[$i] ?? 0;
                $data[$pertanyaanId]['label'][] = $keterangan[$i] . ' (' . $jumlah . ')';
                $data[$pertanyaanId]['data'][] = $jumlah;
            }
        }
        // 
        return view('kalab.kuesioner.grafik_pertanyaan', compact('kuesioner', 'tahun', 'pertanyaan_kuesioners', 'data'));
    }

    public function grafik_prodi($id, $tahun)
    {
        $kuesioner = Kuesioner::select('id', 'singkatan', 'judul')->findOrFail($id);
        // 
        $grafiks = JawabanKuesioner::whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })
            ->when(!empty($tahun), function ($q) use ($tahun) {
                $q->whereYear('created_at', $tahun);
            })
            ->select('peminjam_id', 'pertanyaankuesioner_id', 'jawaban')
            ->with('peminjam:id,subprodi_id')
            ->get()
            ->groupBy('peminjam.subprodi_id');
        // 
        $data = [];
        $subprodiIds = [];
        // 
        $keterangan = [
            1 => 'Tidak Puas',
            2 => 'Kurang Puas',
            3 => 'Puas',
            4 => 'Sangat Puas'
        ];
        // 
        foreach ($grafiks as $subprodiId => $grafik) {
            $jawaban = $grafik->pluck('jawaban')->map('intval')->toArray();
            $jumlah = array_count_values($jawaban);
            $subprodiIds[] = $subprodiId;
            // 
            foreach (range(1, 4) as $i) {
                $count = $jumlah[$i] ?? 0;
                $data[$subprodiId]['label'][] = $keterangan[$i] . ' (' . $count . ')';
                $data[$subprodiId]['data'][] = $count;
            }
        }
        // 
        $subprodis = SubProdi::whereIn('id', $subprodiIds)
            ->select('id', 'jenjang', 'nama')
            ->get();
        // 
        return view('kalab.kuesioner.grafik_prodi', compact('kuesioner', 'tahun', 'subprodis', 'data'));
    }
}
