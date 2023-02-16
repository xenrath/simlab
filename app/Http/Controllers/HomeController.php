<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Barang;
use App\Models\Berita;
use App\Models\Ruang;
use App\Models\User;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // if (!auth()->user()) {
        //     $laborans = User::where('role', 'laboran')->get();
        //     $ruangs = Ruang::get();
        //     $barangs = Barang::get();
        //     $bahans = Bahan::get();
        //     $users = User::where('role', 'kalab')->orWhere('role', 'laboran')->get();
        //     $beritas = Berita::paginate(8);
        //     return view('front.home', compact('laborans', 'ruangs', 'barangs', 'bahans', 'users', 'beritas'));
        // } 
        if (auth()->user()->isDev()) {
            return redirect('dev');
        } elseif (auth()->user()->isAdmin()) {
            return redirect('admin');
        } elseif (auth()->user()->isKalab()) {
            return redirect('kalab');
        } elseif (auth()->user()->isLaboran()) {
            return redirect('laboran');
        } elseif (auth()->user()->isPeminjam()) {
            return redirect('peminjam');
        } elseif (auth()->user()->isWeb()) {
            return redirect('web');
        }
    }

    public function berita($tanggal, $slug)
    {
        $berita = Berita::whereDate('created_at', $tanggal)->where('slug', $slug)->first();
        return view('front.berita', compact('berita'));
    }
}
