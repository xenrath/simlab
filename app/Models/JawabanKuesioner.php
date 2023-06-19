<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanKuesioner extends Model
{
    use HasFactory;

    protected $fillable = [
        'peminjam_id',
        'pertanyaankuesioner_id',
        'jawaban'
    ];

    public function pertanyaankuesioner()
    {
        return $this->belongsTo(PertanyaanKuesioner::class);
    }
}
