<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\StokBarang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StokBarangsImport implements
    // ToCollection,
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        $barang = Barang::where('kode', $row['kode'])->first();
        $stokbarang = StokBarang::create([
            'barang_id' => $barang->id,
            'normal' => $row['normal'],
            'rusak' => $row['rusak'],
            'satuan_id' => $barang->satuan_id,
        ]);

        if ($stokbarang) {
            Barang::where('kode', $row['kode'])->update([
                'normal' => $barang->normal + $row['normal'],
                'rusak' => $barang->rusak + $row['rusak'],
                'total' => $barang->total + $row['normal'] + $row['rusak']
            ]);
        }

        return $stokbarang;
    }

    // public function collection(Collection $rows)
    // {
    //     foreach ($rows as $row) {
    //         $barang = Barang::where('kode', $row['kode'])->first();
    //         StokBarang::create([
    //             'barang_id' => $barang->id,
    //             'normal' => $row['normal'],
    //             'rusak' => $row['rusak'],
    //             'satuan_id' => $barang->satuan_id,
    //         ]);
    //         Barang::where('kode', $row['kode'])->update([
    //             'normal' => $barang->normal + $row['normal'],
    //             'rusak' => $barang->rusak + $row['rusak'],
    //             'total' => $barang->total + $row['normal'] + $row['rusak']
    //         ]);
    //     }
    // }

    public function rules(): array
    {
        return [
            'kode' => 'required',
            '*.kode' => 'required',
            'normal' => 'required|numeric',
            '*.normal' => 'required|numeric',
            'rusak' => 'required|numeric',
            '*.rusak' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'kode.required' => 'Kode harus diisi!',
            'normal.required' => 'Jumlah normal harus diisi!',
            'normal.numeric' => 'Jumlah normal yang dimasukan salah!',
            'rusak.required' => 'Jumlah rusak harus diisi!',
            'rusak.numeric' => 'Jumlah rusak yang dimasukan salah!',
        ];
    }
}
