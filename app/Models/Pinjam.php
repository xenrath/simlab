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
        'tanggal_awal',
        'tanggal_akhir',
        'jam_awal',
        'jam_akhir',
        'matakuliah',
        'dosen',
        'ruang_id',
        'keterangan',
        'laboran_id',
        'bahan',
        'kategori',
        'status'
    ];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class);
    }

    public function peminjam()
    {
        return $this->belongsTo(User::class, 'peminjam_id', 'id');
    }

    public function matakuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id', 'id');
    }

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
