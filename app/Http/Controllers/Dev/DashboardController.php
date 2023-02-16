<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\Bahan;
use App\Models\Barang;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $users = User::get();
        $barangs = Barang::get();
        $bahans = Bahan::get();
        
        return view('dev.index', compact('users', 'barangs', 'bahans'));
    }
}