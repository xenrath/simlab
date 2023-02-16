<?php

namespace App\Imports;

use App\Models\Barang;
use App\Models\Stok;
use App\Models\StokBarang;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class StoksImport implements
    ToModel,
    WithHeadingRow,
    SkipsOnFailure
{
    use Importable, SkipsFailures;

    public function model(array $row)
    {
        $barangs = Barang::get();
        foreach ($barangs as $barang) {
            if ($barang->kode == $row['kode']) {
                return new StokBarang([
                    'barang_id' => $barang->id,
                    'satuan_id' => $barang->satuan_id,
                    'jumlah' => $row['stok'],
                    'created_at' => $barang->created_at,
                ]);
            }
        }
    }
}
