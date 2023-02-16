<?php

namespace App\Http\Controllers;

use App\Models\SubProdi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id;
        $user = User::where('id', $id)->first();
        $subprodis = SubProdi::get();

        // return response($user);

        return view('profile.index', compact('user', 'subprodis'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $user->id . ',id',
            'telp' => 'required|unique:users,telp,' . $user->id . ',id',
            'alamat' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048'
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'telp.required' => 'Nomor Telepon harus ditambahkan!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
            'alamat.required' => 'Alamat harus ditambahkan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $request->foto);
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . random_int(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = $user->foto;
        }

        if ($request->password) {
            $password = bcrypt($request->password);
        } else {
            $password = $user->password;
        }

        $update = User::where('id', $user->id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $namafoto,
            'password' => $password
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui profile');
        } else {
            alert()->error('Error', 'Gagal memperbarui profile!');
        }

        return redirect()->back();
    }
}
