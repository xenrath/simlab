<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->get('role');
        $keyword = $request->get('keyword');

        if ($role != "" && $keyword != "") {
            $users = User::where([
                ['role', $role],
                ['kode', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', $role],
                ['nama', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', $role],
                ['alamat', 'like', "%$keyword%"]
            ])->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($role != "" && $keyword == "") {
            $users = User::where('role', $role)->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($role == "" && $keyword != "") {
            $users = User::where('kode', 'like', "%$keyword%")
                ->orWhere('nama', 'like', "%$keyword%")
                ->orWhere('alamat', 'like', "%$keyword%")
                ->orderBy('nama', 'ASC')
                ->paginate(10);
        } else {
            $users = User::where('role', '!=', 'admin')->orderBy('nama', 'ASC')->paginate(10);
        }

        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'nama' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama user tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Ukuran foto terlalu besar, max 2 MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->foto) {
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = '';
        }

        User::create(array_merge($request->all(), [
            'kode' => $request->username,
            'password' => bcrypt($request->username),
            'foto' => $namafoto
        ]));

        alert()->success('Success', 'Berhasil menambahkan user');

        return redirect('admin/user');
    }

    public function show($id)
    {
        $user = User::find($id);

        return view('admin.user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama user tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Ukuran foto terlalu besar, max 2 MB!',
        ]);

        $user = User::find($id);

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $request->foto);
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = $user->foto;
        }

        $update = $user->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'foto' => $namafoto
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui user');
        } else {
            alert()->error('Error', 'Gagal memperbarui user!');
        }

        return redirect('admin/user');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $delete = $user->delete();

        if ($delete) {
            Storage::disk('local')->delete('public/uploads/' . $user->foto);
            alert()->success('Success', 'Berhasil menghapus user');
        } else {
            alert()->error('Error', 'Gagal menghapus user!');
        }

        return redirect()->back();
    }

    public function profile()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        return view('admin.user.profile', compact('user'));
    }

    public function profileUpdate(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:users,kode,' . $id . ',id',
            'nama' => 'required',
            'telp' => 'required|unique:users,telp,' . $id . ',id',
            'foto' => 'sometimes|image|mimes:jpeg,jpg,png|max:2048',
            'password' => 'sometimes|min:8',
        ], [
            'kode.required' => 'Kode tidak boleh kosong!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama tidak boleh kosong!',
            'telp.required' => 'No. Telepon tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'password.min' => 'Password harus sama atau lebih dari 8 karakter'
        ]);

        $user = User::find($id);

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $request->foto);
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = $user->foto;
        }

        if ($request->password) {
            $password = bcrypt($request->password);
        } else {
            $password = $user->password;
        }

        $update = User::where('id', $id)->update([
            'kode' => $request->kode,
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

    public function reset_password($id)
    {
        $user = User::where('id', $id)->first();

        User::where('id', $id)->update([
            'password' => bcrypt($user->username)
        ]);

        alert()->success('Success', 'Berhasil mereset password');

        return back();
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new UsersImport, $request->file('file'));

        alert()->success('Success', 'Berhasil menambahkan user');

        return back();
    }
}
