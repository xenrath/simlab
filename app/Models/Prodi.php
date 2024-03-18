<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode',
        'nama',
        'singkatan',
        'is_prodi',
        'tempat_id'
    ];

    public function tempat()
    {
        return $this->belongsTo(Tempat::class);
    }
}
