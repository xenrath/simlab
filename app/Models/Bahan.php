<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bahan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'kode',
        'nama',
        'ruang_id',
        'prodi_id',
        'stok',
        'satuan',
        'satuan_pinjam',
        'satuan_id',
        'keterangan',
        'gambar',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }
    
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
    
    public function satuan_o()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id', 'id');
    }

    public function detailpinjams()
    {
        return $this->hasMany(DetailPinjam::class);
    }
}
