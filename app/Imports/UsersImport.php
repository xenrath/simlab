<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        return new User([
            'kode' => $row['username'],
            'username' => $row['username'],
            'nama' => $row['nama'],
            'subprodi_id' => $row['subprodi_id'],
            'role' => 'peminjam',
            'password' => bcrypt($row['username']),
        ]);
    }

    public function rules(): array
    {
        return [
            'username' => 'required|unique:users',
            '*.username' => 'required|unique:users',
            'nama' => 'required',
            '*.nama' => 'required',
            'subprodi_id' => 'required',
            '*.subprodi_id' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'username.required' => 'NIM harus diisi!',
            'username.unique' => 'NIM sudah digunakan!',
            'nama.required' => 'Nama harus diisi!',
            'subprodi_id.required' => 'Prodi ID harus diisi!',
        ];
    }
}
