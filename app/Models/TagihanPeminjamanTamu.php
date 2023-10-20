<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanPeminjamanTamu extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_tamu_id',
        'detail_peminjaman_tamu_id',
        'jumlah'
    ];
}
