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

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id', 'id');
    }

    public function pertanyaankuesioner()
    {
        return $this->belongsTo(PertanyaanKuesioner::class);
    }
}
