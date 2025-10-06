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
        return $this->hasProdi(1);
    }

    public function isPerawat()
    {
        return $this->hasProdi(2);
    }

    public function isK3()
    {
        return $this->hasProdi(3);
    }

    public function isFarmasi()
    {
        return $this->hasProdi(4);
    }

    public function hasProdi($id)
    {
        $prodi_id = $this->prodi_id ?? optional($this->subprodi)->prodi_id;

        return $prodi_id == $id;
    }

    public function isWeb()
    {
        if ($this->role == 'web') {
            return true;
        } else {
            return false;
        }
    }

    // 

    public function isLabTerpadu()
    {
        if ($this->subprodi->prodi->tempat_id == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isFeb()
    {
        if ($this->subprodi_id == 7 || $this->subprodi_id == 9) {
            return true;
        } else {
            return false;
        }
    }

    public function isTi()
    {
        if ($this->subprodi_id == 8) {
            return true;
        } else {
            return false;
        }
    }

    // public function isFarmasi()
    // {
    //     if ($this->subprodi->prodi->tempat_id == 2) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }
}
