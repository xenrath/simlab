<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProdi extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenjang',
        'nama',
        'prodi_id',
    ];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
