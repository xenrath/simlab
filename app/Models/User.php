<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'kode',
        'username',
        'nama',
        'password',
        'password_text',
        'telp',
        'alamat',
        'foto',
        'role',
        'status',
        'subprodi_id',
        'tingkat',
        'prodi_id',
        'is_active'
    ];

    // protected $hidden = [
    //     'password',
    // ];

    protected $dates = ['deleted_at'];

    public function barangs()
    {
        return $this->hasMany(Barang::class);
    }

    public function ruangs()
    {
        return $this->hasMany(Ruang::class, 'laboran_id', 'id');
    }

    public function tempat()
    {
        return $this->hasOne(Tempat::class, 'admin_id', 'id');
    }

    public function subprodi()
    {
        return $this->belongsTo(SubProdi::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function jawaban_kuesioners()
    {
        return $this->hasMany(JawabanKuesioner::class, 'peminjam_id', 'id');
    }

    public function isDev()
    {
        if ($this->role == 'dev') {
            return true;
        } else {
            return false;
        }
    }

    public function isAdmin()
    {
        if ($this->role == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    public function isKalab()
    {
        if ($this->role == 'kalab') {
            return true;
        } else {
            return false;
        }
    }

    public function isLaboran()
    {
        if ($this->role == 'laboran') {
            return true;
        } else {
            return false;
        }
    }

    public function isPeminjam()
    {
        if ($this->role == 'peminjam') {
            return true;
        } else {
            return false;
        }
    }

    public function isBidan()
    {
        if ($this->subprodi->prodi_id == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isPerawat()
    {
        if ($this->subprodi->prodi_id == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function isK3()
    {
        if ($this->subprodi->prodi_id == 3) {
            return true;
        } else {
            return false;
        }
    }

    public function isFarmasi()
    {
        if ($this->subprodi->prodi_id == 4) {
            return true;
        } else {
            return false;
        }
    }

    public function isWeb()
    {
        if ($this->role == 'web') {
            return true;
        } else {
            return false;
        }
    }
}
