<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanKuesioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'kuesioner_id',
        'pertanyaan',
    ];

    public function kuesioner()
    {
        return $this->belongsTo(Kuesioner::class);
    }
}
