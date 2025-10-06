<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapBahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bahan_id',
        'bahan_nama',
        'prodi_id',
        'prodi_nama',
        'jumlah',
        'satuan',
    ];
}
