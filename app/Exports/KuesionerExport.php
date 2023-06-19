<?php

namespace App\Exports;

use App\Models\JawabanKuesioner;
use App\Models\Kuesioner;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KuesionerExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    protected $id;
    protected $tahun;

    public function __construct($id, $tahun)
    {
        $this->id = $id;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $id = $this->id;
        $tahun = $this->tahun;
        $collect = collect();

        $data = JawabanKuesioner::whereYear('created_at', $tahun)->whereHas('pertanyaankuesioner', function ($query) use ($id) {
            $query->where('kuesioner_id', $id);
        })->get()->groupBy('peminjam_id');

        foreach ($data as $key => $jawabans) {
            $user = User::where('id', $key)->first();

            $array = array(
                'nama' => $user->nama,
                'nim' => $user->kode,
                'prodi' => $user->subprodi->jenjang . " " . $user->subprodi->nama,
            );

            foreach ($jawabans as $key => $jawaban) {
                if ($jawaban->jawaban == '4') {
                    $j = "Sangat Puas";
                } elseif ($jawaban->jawaban == '3') {
                    $j = "Puas";
                } elseif ($jawaban->jawaban == '2') {
                    $j = "Kurang Puas";
                } elseif ($jawaban->jawaban == '1') {
                    $j = "Tidak Puas";
                }
                $no = $key + 1;
                $array['pertanyaan ' . $no] = $j;
            }

            $collect->push($array);
        }

        return $collect;
    }

    public function headings(): array
    {
        $id = $this->id;
        $headings = array();

        array_push($headings, 'Nama', 'NIM', 'Prodi');

        $kuesioner = Kuesioner::where('id', $id)->first();

        for ($i = 1; $i <= count($kuesioner->pertanyaan_kuesioners); $i++) {
            array_push($headings, 'Pertanyaan ' . $i);
        }

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }
}
