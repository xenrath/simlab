<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjam extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'peminjam_id',
        'praktik_id',
        'tanggal_awal',
        'tanggal_akhir',
        'jam_awal',
        'jam_akhir',
        'matakuliah',
        'praktik',
        'dosen',
        'kelas',
        'keterangan',
        'ruang_id',
        'laboran_id',
        'bahan',
        'kategori',
        'status'
    ];

    // public function praktik_relation()
    // {
    //     return $this->belongsTo(Praktik::class, 'praktik_id', 'id', 'praktik_relation');
    // }
    
    public function praktik()
    {
        return $this->belongsTo(Praktik::class, 'praktik_id', 'id', 'praktik');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function peminjam()
    {
        return $this->belongsTo(User::class);
    }

    // public function matakuliah()
    // {
    //     return $this->belongsTo(MataKuliah::class);
    // }

    // public function dosen()
    // {
    //     return $this->belongsTo(User::class, 'dosen_id', 'id');
    // }

    public function laboran()
    {
        return $this->belongsTo(User::class, 'laboran_id', 'id');
    }

    public function detail_pinjams()
    {
        return $this->hasMany(DetailPinjam::class);
    }

    public function kelompoks()
    {
        return $this->hasMany(Kelompok::class);
    }
}
