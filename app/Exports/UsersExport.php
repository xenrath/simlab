<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('kode', 'nama', 'gender', 'subprodi_id', 'semester')->get();
    }

    public function headings(): array
    {
        return [
            'username',
            'nama',
            'gender',
            'subprodi_id',
            'semester'
        ];
    }
}
