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

    // public function __construct($role)
    // {
    //     $this->role = $role;
    // }

    public function model(array $row)
    {
        // if ($this->role == "peminjam") {
        return new User([
            'kode' => $row['username'],
            'username' => $row['username'],
            'nama' => $row['nama'],
            'gender' => $row['gender'],
            'subprodi_id' => $row['subprodi_id'],
            'semester' => $row['semester'],
            'role' => 'peminjam',
            'password' => bcrypt($row['username']),
        ]);
        // } else {
        //     return new User([
        //         'kode' => $row['username'],
        //         'username' => $row['username'],
        //         'nama' => $row['nama'],
        //         'role' => $this->role,
        //         'password' => bcrypt($row['username']),
        //     ]);
        // }
    }

    public function rules(): array
    {
        // if ($this->role == "peminjam") {
        return [
            'username' => 'required|unique:users',
            '*.username' => 'required|unique:users',
            'nama' => 'required',
            '*.nama' => 'required',
            'gender' => 'required|in:L,P',
            '*.gender' => 'required|in:L,P',
            'subprodi_id' => 'required',
            '*.subprodi_id' => 'required',
            'semester' => 'required',
            '*.semester' => 'required',
        ];
        // } else {
        //     return [
        //         'username' => 'required|unique:users',
        //         '*.username' => 'required|unique:users',
        //         'nama' => 'required',
        //         '*.nama' => 'required',
        //     ];
        // }
    }

    public function customValidationMessages()
    {
        // if ($this->role == "peminjam") {
        return [
            'username.required' => 'NIM harus diisi!',
            'username.unique' => 'NIM sudah digunakan!',
            'nama.required' => 'Nama harus diisi!',
            'gender.required' => 'Jenis kelamin harus diisi!',
            'gender.in' => 'Jenis kelamin yang dimasukan salah!',
            'subprodi_id.required' => 'Prodi ID harus diisi!',
            'semester.required' => 'Prodi ID harus diisi!',
        ];
        // } else {
        //     return [
        //         'username.required' => 'NIM harus diisi!',
        //         'username.unique' => 'NIM sudah digunakan!',
        //         'nama.required' => 'Nama harus diisi!',
        //     ];
        // }
    }

    // public function telp($telp)
    // {
    //     if (substr(trim($telp), 0, 3) == '+62') {
    //         $cek = substr(trim($telp), 3);
    //     } else if (substr(trim($telp), 0, 2) == '62') {
    //         $cek = substr(trim($telp), 2);
    //     } else if (substr(trim($telp), 0, 1) == '0') {
    //         $cek = substr(trim($telp), 1);
    //     } else {
    //         $cek = $telp;
    //     }

    //     return $cek;
    // }
}
