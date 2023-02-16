<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Ruang;
use App\Models\StokBarang;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BarangsImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    // public function model(array $row)
    // {
    //     $ruang = Ruang::where('kode', $row['ruang_id'])->first();

    //     $barang = new Barang([
    //         'kode' => $this->generateCode($ruang->id),
    //         'nama' => $row['nama'],
    //         'ruang_id' => $ruang->id,
    //         'normal' => $row['normal'],
    //         'rusak' => $row['rusak'],
    //         'total' => $row['normal'] + $row['rusak'],
    //         'satuan_id' => '6',
    //     ]);

    //     new StokBarang([
    //         'barang_id' => $barang->id,
    //         'normal' => $barang->normal,
    //         'rusak' => $barang->rusak,
    //         'satuan_id' => $barang->satuan_id,
    //     ]);

    //     return $barang;
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $ruang = Ruang::where('kode', $row['ruang_id'])->first();
            $barang = Barang::create([
                'kode' => $this->generateCode($ruang->id),
                'nama' => $row['nama'],
                'ruang_id' => $ruang->id,
                'normal' => $row['normal'],
                'rusak' => $row['rusak'],
                'total' => $row['normal'] + $row['rusak'],
                'satuan_id' => '6',
            ]);
            StokBarang::create([
                'barang_id' => $barang->id,
                'normal' => $barang->normal,
                'rusak' => $barang->rusak,
                'satuan_id' => $barang->satuan_id,
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            '*.nama' => 'required',
            'ruang_id' => 'required',
            '*.ruang_id' => 'required',
            'normal' => 'required|numeric',
            '*.normal' => 'required|numeric',
            'rusak' => 'required|numeric',
            '*.rusak' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama barang harus diisi!',
            'ruang_id.required' => 'Ruang harus diisi!',
            'normal.required' => 'Jumlah normal harus diisi!',
            'normal.numeric' => 'Jumlah normal yang dimasukan salah!',
            'rusak.required' => 'Jumlah rusak harus diisi!',
            'rusak.numeric' => 'Jumlah rusak yang dimasukan salah!',
        ];
    }

    public function generateCode($id)
    {
        $barangs = Barang::where('ruang_id', $id)->withTrashed()->get();
        $barang = Barang::where('ruang_id', $id)->orderByDesc('kode')->withTrashed()->first();
        $ruang = Ruang::where('id', $id)->first();
        if (count($barangs) > 0) {
            $last = substr($barang->kode, 15);
            $jumlah = (int)$last + 1;
            $urutan = sprintf('%03s', $jumlah);
        } else {
            $urutan = "001";
        }

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".01." . $urutan;
        return $kode;
    }
}
