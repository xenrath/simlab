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
    public function index()
    {
        $users = User::with('prodi:id,singkatan')
            ->select(
                'id',
                'nama',
                'prodi_id',
                'telp',
                'alamat',
            )
            ->where('role', 'laboran')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.laboran.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::select('id', 'singkatan')->orderBy('singkatan')->get();

        return view('admin.laboran.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|unique:users,kode',
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodis,id',
            'telp' => 'nullable|unique:users,telp',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'Username tidak boleh kosong!',
            'kode.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'prodi_id.exists' => 'Prodi tidak ditemukan!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Foto maksimal ukuran 2MB!',
        ]);

        // Handle Upload Foto (jika ada)
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = 'user/laboran/' . $validated['kode'] . '_' . random_int(10, 99) . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/uploads', $fotoPath);
        }

        // Simpan User
        User::create([
            'kode' => $validated['kode'],
            'username' => $validated['kode'],
            'password' => bcrypt($validated['kode']),
            'nama' => $validated['nama'],
            'prodi_id' => $validated['prodi_id'],
            'telp' => $validated['telp'] ?? null,
            'alamat' => $request->alamat,
            'foto' => $fotoPath,
            'role' => 'laboran',
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
        $user = User::select(
            'id',
            'username',
            'nama',
            'telp',
            'alamat',
            'foto',
            'prodi_id'
        )
            ->findOrFail($id);

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
        $user = User::findOrFail($id);

        if ($user->foto && Storage::exists('public/uploads/' . $user->foto)) {
            Storage::delete('public/uploads/' . $user->foto);
        }

        $user->delete();

        alert()->success('Success', 'Berhasil menghapus Laboran');
        return back();
    }

    public function reset_password($id)
    {
        $user = User::select('id', 'username')->findOrFail($id);
        $user->update([
            'password' => bcrypt($user->username),
        ]);
        alert()->success('Success', 'Berhasil mereset Password');
        return redirect('admin/laboran');
    }
}
