<?php

namespace App\Imports;

use App\Models\Bahan;
use App\Models\Ruang;
use App\Models\StokBahan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BahansImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    // public function model(array $row)
    // {
    //     $ruang = Ruang::where('kode', $row['ruang_id'])->first();
    //     return new Bahan([
    //         'kode' => $this->generateCode($ruang->id),
    //         'nama' => $row['nama'],
    //         'ruang_id' => $ruang->id,
    //         'stok' => $row['stok'],
    //         'satuan_id' => $row['satuan_id'],
    //     ]);
    // }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $ruang = Ruang::where('kode', $row['ruang_id'])->first();
            $bahan = Bahan::create([
                'kode' => $this->generateCode($ruang->id),
                'nama' => $row['nama'],
                'ruang_id' => $ruang->id,
                'stok' => $row['stok'],
                'satuan_id' => $row['satuan_id'],
            ]);
            StokBahan::create([
                'bahan_id' => $bahan->id,
                'stok' => $bahan->stok,
                'satuan_id' => $bahan->satuan_id,
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
            'stok' => 'required|numeric',
            '*.stok' => 'required|numeric',
            'satuan_id' => 'required',
            '*.satuan_id' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama bahan harus diisi!',
            'ruang_id.required' => 'Ruangan harus diisi!',
            'stok.required' => 'Stok bahan harus diisi!',
            'stok.numeric' => 'Stok yang dimasukan salah!',
            'satuan_id.required' => 'Satuan harus diisi!',
        ];
    }

    public function generateCode($id)
    {
        $bahans = Bahan::where('ruang_id', $id)->withTrashed()->get();
        $bahan = Bahan::where('ruang_id', $id)->orderByDesc('kode')->withTrashed()->first();
        $ruang = Ruang::where('id', $id)->first();
        if (count($bahans) > 0) {
            $last = substr($bahan->kode, 15);
            $jumlah = (int)$last + 1;
            $urutan = sprintf('%03s', $jumlah);
        } else {
            $urutan = "001";
        }

        $kode = $ruang->tempat->kode . "." . $ruang->lantai . "." . $ruang->prodi->kode . "." . $ruang->kode . ".02." . $urutan;
        return $kode;
    }
}
