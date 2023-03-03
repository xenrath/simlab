<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsenController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'peminjam')->get();
        return view('absen.index', compact('users'));
    }

    public function store(Request $request)
    {
        $check = $request->check;
        $username = $request->username;

        if ($check) {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
            ], [
                'username.required' => 'NIM harus diisi!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'institusi' => 'required',
            ], [
                'username.required' => 'Nama harus diisi!',
                'institusi.required' => 'Institusi harus diisi!',
            ]);
        }

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($check) {
            $user = User::where('kode', $username)->first();

            if ($user) {
                $institusi = $user->subprodi->jenjang . " " . $user->subprodi->nama;
                Absen::create([
                    'user_id' => $user->id,
                    'username' => $username,
                    'institusi' => $institusi,
                ]);
                alert()->success('Success', 'Selamat Datang ' . $user->nama);
                return back();
            } else {
                return back()->withInput()->with('error', array('Mahasiswa tidak ditemukan!'));
            }
        } else {
            $institusi = $request->institusi;
            Absen::create([
                'user_id' => null,
                'username' => $username,
                'institusi' => $institusi,
            ]);

            alert()->success('Success', 'Selamat Datang ' . $username . ' -' . $institusi);

            return back();
        }
    }
}
