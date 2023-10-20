<?php

namespace App\Http\Controllers\Peminjam\Peminjaman\Create;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('peminjam.peminjaman-new.create.index');
    }
}
