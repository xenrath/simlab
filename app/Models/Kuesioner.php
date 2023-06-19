<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuesioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
    ];
    
    public function pertanyaan_kuesioners()
    {
        return $this->hasMany(PertanyaanKuesioner::class);
    }
}
