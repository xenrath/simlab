<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'tempat_id',
        'is_praktik',
        'prodi_id',
        'laboran_id',
    ];

    public function tempat()
    {
        return $this->belongsTo(Tempat::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function laboran()
    {
        return $this->belongsTo(User::class);
    }

    public function pinjams()
    {
        return $this->hasMany(Pinjam::class);
    }
}
