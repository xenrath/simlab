<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeminjamanTamu extends Model
{
    use HasFactory;

    protected $fillable = [
        'tamu_id',
        'lama',
        'keperluan',
        'tanggal_awal',
        'tanggal_akhir',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function tamu()
    {
        return $this->belongsTo(Tamu::class);
    }

    public function detail_peminjaman_tamus()
    {
        return $this->hasMany(DetailPeminjamanTamu::class);
    }
}
