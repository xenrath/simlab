<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TagihanPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'tagihan_peminjamans';
    
    protected $fillable = [
        'pinjam_id',
        'detail_pinjam_id',
        'jumlah'
    ];

    public function pinjam()
    {
        return $this->belongsTo(Pinjam::class);
    }

    public function detail_pinjam()
    {
        return $this->belongsTo(DetailPinjam::class);
    }
}
