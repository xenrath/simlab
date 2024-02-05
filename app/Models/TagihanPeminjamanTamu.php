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

    public function peminjaman_tamu()
    {
        return $this->belongsTo(PeminjamanTamu::class);
    }
    
    public function detail_peminjaman_tamu()
    {
        return $this->belongsTo(DetailPeminjamanTamu::class);
    }
}
