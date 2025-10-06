<?php

namespace App\Imports;

use App\Models\Bahan;
use App\Models\Prodi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BahansImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function collection(Collection $rows)
    {
        $rows = $rows->filter(function ($row) {
            return !empty($row['nama']) && !empty($row['prodi_id']) && !empty($row['satuan_pinjam']);
        });

        foreach ($rows as $row) {
            $prodi_prefix = Prodi::where('id', $row['prodi_id'])->value('kode');
            $kode = $this->generate_kode_bahan($prodi_prefix);

            Bahan::create([
                'kode' => $kode,
                'nama' => $row['nama'],
                'prodi_id' => $row['prodi_id'],
                'satuan_pinjam' => $row['satuan_pinjam'],
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.nama' => 'sometimes|required',
            '*.prodi_id' => 'sometimes|required',
            '*.satuan_pinjam' => 'sometimes|required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nama.required' => 'Nama Bahan harus diisi!',
            'prodi_id.required' => 'Prodi harus diisi!',
            'satuan_pinjam.required' => 'Satuan harus diisi!',
        ];
    }

    public function generate_kode_bahan($prodi_prefix)
    {
        do {
            $random = rand(10000000, 99999999); // 8 digit random
            $kode = strtoupper($prodi_prefix) . '-' . $random;
        } while (Bahan::where('kode', $kode)->exists());

        return $kode;
    }
}
