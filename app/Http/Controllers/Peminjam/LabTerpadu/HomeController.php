<?php

namespace App\Http\Controllers\Peminjam\LabTerpadu;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('peminjam.labterpadu.index');
    }
}
