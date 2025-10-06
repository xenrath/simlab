<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PinjamDetailBahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinjam_id',
        'bahan_id',
        'bahan_nama',
        'prodi_id',
        'prodi_nama',
        'jumlah',
        'satuan',
    ];

    public function pinjam()
    {
        return $this->belongsTo(Pinjam::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }
    
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
