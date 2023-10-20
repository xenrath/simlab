<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamu extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'telp',
        'institusi',
        'alamat',
    ];

    protected $hidden = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function peminjaman_tamus()
    {
        return $this->hasMany(PeminjamanTamu::class);
    }
}
