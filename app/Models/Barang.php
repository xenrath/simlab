<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Laravel\Scout\Searchable;

class Barang extends Model
{
    use HasFactory,
        // Searchable,
        SoftDeletes;

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

    public function toSearchableArray()
    {
        $this->loadMissing('ruang');

        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'ruang_id' => $this->ruang_id,
            'ruang_nama' => $this->ruang->nama ?? null,
            'ruang_tempat_id' => $this->ruang->tempat_id ?? null,
        ];
    }
}
