<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokBahan extends Model
{
    use HasFactory;

    protected $fillable = [
        'bahan_id',
        'stok',
        'satuan_id',
    ];

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function satuan()
    {
        return $this->belongsTo(Satuan::class);
    }
}
