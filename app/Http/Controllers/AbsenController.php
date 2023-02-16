<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\User;
use Illuminate\Http\Request;

class AbsenController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'peminjam')->get();
        return view('absen.index', compact('users'));
    }

    public function store(Request $request)
    {
        $username = $request->username;
        $institusi = $request->institusi;

        if ($username && $institusi) {
            $absen = Absen::create($request->all());
            return back()
                ->with('status', true)
                ->with('role', 'tamu')
                ->with('success', $absen);
        } else if ($username) {
            $user = User::where('kode', $username)->first();
            if ($user) {
                $absen = Absen::create(array_merge($request->all(), [
                    'user_id' => $user->id
                ]));
                if ($absen) {
                    return back()
                        ->with('status', true)
                        ->with('role', 'mahasiswa')
                        ->with('success', $absen);
                } else {
                    return back()
                        ->with('status', true)
                        ->with('role', 'mahasiswa')
                        ->with('success', $absen);
                }
            } else {
                return back()
                    ->with('status', true)
                    ->with('error', 'Mahasiswa dengan NIM ' . $username . ' tidak ditemukan!')
                    ->withInput();
            }
        } else {
            return back()
                ->with('status', true)
                ->with('error', 'Masukan data dengan benar!');
        }
    }
}
