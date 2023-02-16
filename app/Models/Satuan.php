<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'singkatan',
        'kali',
        'kategori'
    ];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }
}
