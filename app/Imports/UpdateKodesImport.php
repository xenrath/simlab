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

class UpdateKodesImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        $barang = Barang::where([
            ['nama', $row['nama']],
            ['ruang_id', $row['ruang_id']]
        ])->first();

        if ($barang) {
            Barang::where('id', $barang->id)->update([
                'kode' => $row['kode']
            ]);
        }

        return $barang;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required',
            '*.nama' => 'required',
            'ruang_id' => 'required',
            '*.ruang_id' => 'required',
            'kode' => 'required',
            '*.kode' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama barang harus diisi!',
            'ruang_id.required' => 'Ruang harus diisi!',
            'kode.required' => 'Kode harus diisi!',
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
