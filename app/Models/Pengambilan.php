<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengambilan extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruang_id',
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function detailpengambilans()
    {
        return $this->hasMany(DetailPengambilan::class);
    }
}
