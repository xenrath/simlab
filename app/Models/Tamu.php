<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tamu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nama',
        'telp',
        'institusi',
        'alamat',
    ];

    public function peminjaman_tamus()
    {
        return $this->hasMany(PeminjamanTamu::class);
    }
}
