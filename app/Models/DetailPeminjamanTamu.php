<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjamanTamu extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjaman_tamu_id',
        'barang_id',
        'total',
        'normal',
        'rusak',
        'hilang',
        'status',
    ];

    public function peminjaman_tamu()
    {
        return $this->belongsTo(PeminjamanTamu::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
