<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'ruang_id',
        'normal',
        'rusak',
        'total',
        'satuan_id',
        'keterangan',
        'gambar',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function stokbarangs()
    {
        return $this->hasMany(StokBarang::class);
    }

    public function detailpinjams()
    {
        return $this->hasMany(DetailPinjam::class);
    }
}
