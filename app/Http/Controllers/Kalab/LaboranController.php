<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaboranController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword != "") {
            $users = User::where([
                ['role', 'laboran'],
                ['kode', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', 'laboran'],
                ['nama', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', 'laboran'],
                ['alamat', 'like', "%$keyword%"]
            ])->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $users = User::where('role', 'laboran')
                ->orderBy('nama', 'ASC')
                ->paginate(10);
        }

        return view('kalab.laboran.index', compact('users'));
    }

    // public function create()
    // {
    //     $prodis = Prodi::get();
    //     $ruangs = Ruang::get();

    //     return view('admin.laboran.create', compact('prodis', 'ruangs'));
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|unique:users',
    //         'nama' => 'required',
    //         'telp' => 'nullable|unique:users',
    //         'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
    //     ], [
    //         'username.required' => 'Username tidak boleh kosong!',
    //         'username.unique' => 'Username sudah digunakan!',
    //         'nama.required' => 'Nama Lengkap tidak boleh kosong!',
    //         'telp.unique' => 'Nomor Telepon sudah digunakan!',
    //         'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
    //     ]);

    //     if ($validator->fails()) {
    //         $error = $validator->errors()->all();
    //         return back()->withInput()->with('status', $error);
    //     }

    //     if ($request->foto) {
    //         $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
    //         $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
    //         $request->foto->storeAs('public/uploads/', $namafoto);
    //     } else {
    //         $namafoto = null;
    //     }

    //     $user = User::create(array_merge($request->all(), [
    //         'password' => bcrypt($request->username),
    //         'role' => 'laboran',
    //         'foto' => $namafoto
    //     ]));

    //     if ($user) {
    //         alert()->success('Success', 'Berhasil menambahkan Laboran');
    //     } else {
    //         alert()->error('Error', 'Gagal menambahkan Laboran!');
    //     }

    //     return redirect('admin/laboran');
    // }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        // return response($user);

        return view('kalab.laboran.show', compact('user'));
    }

    // public function edit($id)
    // {
    //     $user = User::find($id);

    //     return view('admin.laboran.edit', compact('user'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required|unique:users,username,' . $id . ',id',
    //         'nama' => 'required',
    //         'telp' => 'nullable|unique:users,telp,' . $id . ',id',
    //         'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
    //     ], [
    //         'username.required' => 'Username tidak boleh kosong!',
    //         'username.unique' => 'Username sudah digunakan!',
    //         'nama.required' => 'Nama Lengkap tidak boleh kosong!',
    //         'telp.unique' => 'Nomor Telepon sudah digunakan!',
    //         'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
    //     ]);

    //     if ($validator->fails()) {
    //         $error = $validator->errors()->all();
    //         return back()->withInput()->with('status', $error);
    //     }

    //     $user = User::find($id);

    //     if ($request->foto) {
    //         Storage::disk('local')->delete('public/uploads/' . $user->foto);
    //         $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
    //         $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
    //         $request->foto->storeAs('public/uploads/', $namafoto);
    //     } else {
    //         $namafoto = $user->foto;
    //     }

    //     $update = $user->update([
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'telp' => $request->telp,
    //         'alamat' => $request->alamat,
    //         'foto' => $namafoto
    //     ]);

    //     if ($update) {
    //         alert()->success('Success', 'Berhasil memperbarui Laboran');
    //     } else {
    //         alert()->error('Error', 'Gagal memperbarui Laboran!');
    //     }

    //     return redirect('admin/laboran');
    // }

    // public function destroy($id)
    // {
    //     $user = User::find($id);

    //     try {
    //         $user->delete();
    //         Storage::disk('local')->delete('public/uploads/' . $user->foto);
    //     } catch (\Throwable $th) {
    //         return back()->with('error', 'Laboran masih memiliki tanggung jawab pada ruang lab !');
    //     }

    //     alert()->success('Success', 'Berhasil menghapus Laboran');

    //     return back();
    // }
}
