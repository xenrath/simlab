<?php

namespace App\Http\Controllers;

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
            alert()->error('Error', 'Isi data dengan benar!');
            return back()->withInput()->withErrors($validator);
        }

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect('/');
        } else {
            alert()->error('Error', 'Username atau Password salah!');
            return back()->withInput();
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
            'telp' => 'nullable|unique:users,telp,' . auth()->user()->id . ',id',
        ], [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'telp.unique' => 'Nomor WhatsApp sudah digunakan!',
        ]);
        
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Profile!');
            return back()->withInput()->withErrors($validator->errors())->with('profile', true);
        }
        
        $update = User::where('id', auth()->user()->id)->update([
            'nama' => $request->nama,
            'telp' => $request->telp,
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui Profile');
        } else {
            alert()->error('Error', 'Gagal memperbarui Profile!');
        }

        return back();
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
            alert()->error('Error', 'Gagal memperbarui Password!');
            return back()->withInput()->withErrors($validator->errors())->with('password', true);
        }

        $user = User::where('id', auth()->user()->id)->update([
            'password' => bcrypt($request->password),
            'password_text' => $request->password,
        ]);

        if ($user) {
            alert()->success('Success', 'Berhasil memperbarui Profile');
        } else {
            alert()->error('Error', 'Gagal memperbarui Profile!');
        }
        
        return back();
    }
}
