<?php

namespace App\Http\Controllers;

use App\Models\Absen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        if (auth()->check()) {
            if (auth()->user()->isDev()) {
                return redirect('dev');
            }
            if (auth()->user()->isAdmin()) {
                return redirect('admin');
            }
            if (auth()->user()->isKalab()) {
                return redirect('kalab');
            }
            if (auth()->user()->isLaboran()) {
                return redirect('laboran');
            }
            if (auth()->user()->isPeminjam()) {
                return redirect('peminjam');
            }
            if (auth()->user()->isWeb()) {
                return redirect('web');
            }
        }

        return redirect('login');
    }

    public function login()
    {
        if (auth()->check()) {
            return redirect('/');
        } else {
            return view('login');
        }
    }

    public function login_proses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ], [
            'username.required' => 'Username harus diisi!',
            'password.required' => 'Password harus diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator)
                ->with('error', 'Isi data dengan benar!');
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();

            if (auth()->user()->isPeminjam()) {
                $user = auth()->user();
                Absen::create([
                    'nama'   => $user->nama,
                    'nim'  => $user->kode,
                    'prodi' => $user->subprodi->jenjang . " " . $user->subprodi->nama,
                ]);
            }

            return redirect('/');
        } else {
            return back()->withInput()->with('error', 'Username atau Password salah!');
        }
    }

    public function logout()
    {
        if (auth()->check()) {
            Auth::logout();
        }

        return redirect('login');
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'telp' => 'required|unique:users,telp,' . auth()->user()->id . ',id',
        ], [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'telp.required' => 'Nomor WhatsApp harus diisi!',
            'telp.unique' => 'Nomor WhatsApp sudah digunakan!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('profile', true)
                ->with('error', 'Gagal memperbarui Profile!');
        }

        $update = User::where('id', auth()->user()->id)->update([
            'nama' => $request->nama,
            'telp' => $request->telp,
        ]);

        if (!$update) {
            return back()->with('error', 'Gagal memperbarui Profile!');
        }

        return back()->with('success', 'Berhasil memperbarui Profile');
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'Password Baru harus diisi!',
            'password.confirmed' => 'Konfirmasi Password tidak sesuai!',
            'password_confirmation.required' => 'Konfirmasi Password harus diisi!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('password', true)
                ->with('error', 'Gagal memperbarui Password!');
        }

        $update = User::where('id', auth()->user()->id)->update([
            'password' => bcrypt($request->password),
            'password_text' => $request->password,
        ]);

        if (!$update) {
            return back()->with('error', 'Gagal memperbarui Password!');
        }

        return back();
    }
}
