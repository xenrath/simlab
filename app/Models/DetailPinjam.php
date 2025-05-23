<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPinjam extends Model
{
    use HasFactory;

    protected $fillable = [
        'pinjam_id',
        'barang_id',
        'jumlah',
        'satuan_id',
        'normal',
        'rusak',
        'hilang',
        'pelakus',
        'kelompok_id',
        'status',
    ];

    protected $casts = [
        'pelakus' => 'array'
    ];

    public function pinjam()
    {
        return $this->belongsTo(Pinjam::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class)->withTrashed();
    }

    // Join ruang_id
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id', 'id');
    }
}
