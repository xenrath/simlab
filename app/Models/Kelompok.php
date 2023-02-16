<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelompok extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinjam_id',
        'nama',
        'ketua',
        'anggota',
        'shift',
        'jam'
    ];

    protected $casts = [
        'anggota' => 'array'
    ];

    public function m_ketua()
    {
        return $this->belongsTo(User::class, 'ketua', 'kode');
    }
}
