<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KalabController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword != "") {
            $users = User::where([
                ['role', 'kalab'],
                ['kode', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', 'kalab'],
                ['nama', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', 'kalab'],
                ['alamat', 'like', "%$keyword%"]
            ])->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $users = User::where('role', 'kalab')->orderBy('nama', 'ASC')->paginate(10);
        }

        return view('admin.kalab.index', compact('users'));
    }

    public function create()
    {
        return view('admin.kalab.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'nama' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        if ($request->foto) {
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = '';
        }

        $user = User::create(array_merge($request->all(), [
            'password' => bcrypt($request->username),
            'role' => 'kalab',
            'foto' => $namafoto
        ]));

        if ($user) {
            alert()->success('Success', 'Berhasil menambahkan Kalab');
        } else {
            alert()->error('Error', 'Gagal menambahkan Kalab!');
        }

        return redirect('admin/kalab');
    }

    public function show($id)
    {
        $user = User::find($id);

        return view('admin.kalab.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('admin.kalab.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $id . ',id',
            'nama' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'telp.nullable' => 'Nomor Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

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
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $namafoto
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui Kalab');
        } else {
            alert()->error('Error', 'Gagal memperbarui Kalab!');
        }

        return redirect('admin/kalab');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $delete = $user->delete();

        if ($delete) {
            Storage::disk('local')->delete('public/uploads/' . $user->foto);
            alert()->success('Success', 'Berhasil menghapus Kalab');
        } else {
            alert()->error('Error', 'Gagal menghapus Kalab!');
        }

        return back();
    }
}
