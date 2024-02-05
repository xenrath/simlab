<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LaboranController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'laboran')
            ->select(
                'id',
                'nama',
                'prodi_id'
            )
            ->with('prodi:id,singkatan')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.laboran.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::select('id', 'singkatan')->get();

        return view('admin.laboran.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:users',
            'nama' => 'required',
            'prodi_id' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'Username tidak boleh kosong!',
            'kode.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Foto maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->foto) {
            $foto = 'user/laboran/' . $request->kode . '_' . random_int(10, 99) . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = null;
        }

        User::create([
            'kode' => $request->kode,
            'username' => $request->kode,
            'nama' => $request->nama,
            'password' => bcrypt($request->username),
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'role' => 'laboran',
            'prodi_id' => $request->prodi_id,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Laboran');

        return redirect('admin/laboran');
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'username',
                'prodi_id',
                'nama',
                'telp',
                'alamat',
            )
            ->with('prodi:id,singkatan', 'ruangs:laboran_id,nama')
            ->first();

        return view('admin.laboran.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'username',
                'nama',
                'telp',
                'alamat',
                'foto',
                'prodi_id',
            )
            ->first();
        $prodis = Prodi::select('id', 'singkatan')->get();

        return view('admin.laboran.edit', compact('user', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $id . ',id',
            'nama' => 'required',
            'prodi_id' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $user_foto = User::where('id', $id)->value('foto');

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $user_foto);
            $foto = 'user/laboran/' . $request->username . '_' . random_int(10, 99) . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = $user_foto;
        }

        User::where('id', $id)->update([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'prodi_id' => $request->prodi_id
        ]);

        alert()->success('Success', 'Berhasil memperbarui Laboran');

        return redirect('admin/laboran');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        Storage::disk('local')->delete('public/uploads/' . $user->foto);
        
        $user->delete();

        alert()->success('Success', 'Berhasil menghapus Laboran');

        return back();
    }

    public function reset_password($id)
    {
        $username = User::where('id', $id)->value('username');

        User::where('id', $id)->update([
            'password' => bcrypt($username)
        ]);

        alert()->success('Success', 'Berhasil mereset Password');

        return redirect('admin/laboran');
    }
}
