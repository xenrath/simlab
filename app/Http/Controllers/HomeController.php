<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            if (auth()->user()->isLabTerpadu()) {
                if (auth()->user()->isFeb()) {
                    return redirect('peminjam/feb');
                } elseif (auth()->user()->isTi()) {
                    return redirect('peminjam/ti');
                } else {
                    return redirect('peminjam/labterpadu');
                }
            } elseif (auth()->user()->isFarmasi()) {
                return redirect('peminjam/farmasi');
            }
        } elseif (auth()->user()->isWeb()) {
            return redirect('web');
        }
    }

    public function anggota_get(Request $request)
    {
        $anggota_item = $request->anggota_item ?? array();

        if (count($anggota_item)) {
            $anggotas = User::where('role', 'peminjam')
                ->whereIn('id', $anggota_item)
                ->select('id', 'kode', 'nama')
                ->orderBy('kode')
                ->get();
            return $anggotas;
        } else {
            return array();
        }
    }
}
