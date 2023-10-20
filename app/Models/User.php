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
        'username',
        'name',
        'password',
        'password_text',
        'phone',
        'address',
        'photo',
        'role',
        'status'
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

    public function isWeb()
    {
        if ($this->role == 'web') {
            return true;
        } else {
            return false;
        }
    }
}
