<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laboran extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nipy',
        'telp',
        'alamat'
    ];
}
