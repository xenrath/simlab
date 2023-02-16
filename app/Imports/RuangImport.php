<?php

namespace App\Imports;

use App\Models\Ruang;
use Maatwebsite\Excel\Concerns\ToModel;

class RuangImport implements ToModel
{
    public function model(array $row)
    {
        return new Ruang([
            'kode' => $row['kode'],
            'nama' => $row['nama'],
            'telp' => $this->telp($row['telp']),
            'role' => $this->role,
            'password' => bcrypt($row['kode']),
        ]);
    }
}
