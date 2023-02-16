<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'username',
        'institusi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
