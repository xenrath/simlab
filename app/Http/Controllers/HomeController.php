<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // SEOMeta::setTitle('Home');

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
}
