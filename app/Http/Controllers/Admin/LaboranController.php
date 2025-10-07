<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
                'is_pengelola_bahan',
            )
            ->where('role', 'laboran')
            ->orderBy('prodi_id')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin.laboran.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::select('id', 'nama')->where('is_prodi', true)->get();

        return view('admin.laboran.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:users,kode',
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodis,id',
        ], [
            'kode.required' => 'Username tidak boleh kosong!',
            'kode.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'prodi_id.exists' => 'Prodi tidak ditemukan!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal menambahkan Laboran!');
        }

        User::create([
            'kode' => $request->kode,
            'username' => $request->kode,
            'password' => bcrypt($request->kode),
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'is_pengelola_bahan' => $request->is_pengelola_bahan ?? false,
            'role' => 'laboran',
        ]);

        return redirect('admin/laboran')->with('success', 'Berhasil menambahkan Laboran');
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
            'prodi_id'
        )
            ->findOrFail($id);
        $prodis = Prodi::select('id', 'nama')->where('is_prodi', true)->get();

        return view('admin.laboran.edit', compact('user', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $id . ',id',
            'nama' => 'required',
            'prodi_id' => 'required',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal memperbarui Laboran!');
        }

        User::where('id', $id)->update([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'is_pengelola_bahan' => $request->is_pengelola_bahan ?? false,
        ]);

        return redirect('admin/laboran')->with('success', 'Berhasil memperbarui Laboran');
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
