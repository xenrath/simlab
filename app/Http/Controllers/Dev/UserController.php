<?php

namespace App\Http\Controllers\Dev;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\SubProdi;
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
                ['role', '!=', 'dev'],
                ['role', $role],
                ['kode', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', '!=', 'dev'],
                ['role', $role],
                ['nama', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', '!=', 'dev'],
                ['role', $role],
                ['alamat', 'like', "%$keyword%"]
            ])->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($role != "" && $keyword == "") {
            $users = User::where([
                ['role', '!=', 'dev'],
                ['role', $role]
            ])->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($role == "" && $keyword != "") {
            $users = User::where([
                ['role', '!=', 'dev'],
                ['kode', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', '!=', 'dev'],
                ['nama', 'like', "%$keyword%"]
            ])->orWhere([
                ['role', '!=', 'dev'],
                ['alamat', 'like', "%$keyword%"]
            ])->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $users = User::where('role', '!=', 'dev')->orderBy('nama', 'ASC')->paginate(10);
        }

        return view('dev.user.index', compact('users'));
    }

    public function create()
    {
        $subprodis = SubProdi::get();

        return view('dev.user.create', compact('subprodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users|min:6',
            'nama' => 'required',
            'role' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama user tidak boleh kosong!',
            'role.required' => 'Role tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($request->foto) {
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . random_int(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = '';
        }

        if ($request->role == 'peminjam') {
            $kode = $request->username;
        } else {
            $kode = null;
        }

        $user = User::create(array_merge($request->all(), [
            'kode' => $kode,
            'password' => bcrypt($request->username),
            'foto' => $namafoto
        ]));

        if ($user) {
            alert()->success('Success', 'Berhasil menambahkan user');
        } else {
            alert()->error('Error', 'Gagal menambahkan user!');
        }

        return redirect('dev/user');
    }

    public function show($id)
    {
        $user = User::find($id);

        return view('dev.user.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);

        return view('dev.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $id . ',id',
            'nama' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama user tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'role.required' => 'Role user harus dipilih!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        $user = User::find($id);

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

        $update = $user->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'foto' => $namafoto,
            'password' => $password
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui user');
        } else {
            alert()->error('Error', 'Gagal memperbarui user!');
        }

        return redirect('dev/user');
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

        return back();
    }

    public function trash()
    {
        $users = User::onlyTrashed()->paginate(10);
        return view('dev.user.trash', compact('users'));
    }

    public function restore($id = null)
    {
        if ($id != null) {
            User::where('id', $id)->onlyTrashed()->restore();
        } else {
            User::onlyTrashed()->restore();
        }

        alert()->success('Success', 'Berhasil memulihkan User');

        return back();
    }

    public function delete($id = null)
    {
        if ($id != null) {
            User::where('id', $id)->onlyTrashed()->forceDelete();
        } else {
            User::onlyTrashed()->forceDelete();
        }

        alert()->success('Success', 'Berhasil menghapus User');

        return back();
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import.xlsx');
        return response()->download($file);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ], [
            'file.required' => 'File harus ditambahkan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->with('error', $error);
        }

        $file = $request->file('file');

        $import = new UsersImport();
        $import->import($file);

        // dd($import->failures());

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        } else {
            alert()->success('Success', 'Berhasil menambahkan Peminjam');
        }

        return redirect('dev/user');
    }
}
