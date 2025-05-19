<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BarangRusakExport implements FromCollection, WithHeadings, ShouldAutoSize, WithStyles
{
    public function collection()
    {
        $collect = collect();

        $barangs = Barang::where('rusak', '>', '0')
            ->select(
                'nama',
                'rusak',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->get();

        foreach ($barangs as $key => $barang) {
            $array = array(
                'no' => $key + 1,
                'nama' => $barang->nama,
                'rusak' => $barang->rusak . " Pcs",
                'ruang_nama' => $barang->ruang->nama
            );

            $collect->push($array);
        }


        return $collect;
    }

    public function headings(): array
    {
        $headings = array(
            'No', 'Nama Barang', 'Jumlah Rusak', 'Ruang Barang'
        );

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]]
        ];
    }
}
