<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengambilan extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengambilan_id',
        'bahan_id',
        'jumlah',
        'satuan_id',
    ];

    public function pengambilan()
    {
        return $this->belongsTo(Pengambilan::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
