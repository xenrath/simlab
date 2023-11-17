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
            if (auth()->user()->isBidan()) {
                return redirect('peminjam/bidan');
            } elseif (auth()->user()->isPerawat()) {
                return redirect('peminjam/perawat');
            } elseif (auth()->user()->isK3()) {
                return redirect('peminjam/k3');
            } elseif (auth()->user()->isFarmasi()) {
                return redirect('peminjam');
            }
        } elseif (auth()->user()->isWeb()) {
            return redirect('web');
        }
    }
}
