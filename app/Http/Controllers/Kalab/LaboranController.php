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
        $users = User::where('role', 'laboran')
            ->select('id', 'nama')
            ->with('ruangs', function ($query) {
                $query->select('nama', 'laboran_id');
            })
            ->paginate(10);

        return view('kalab.laboran.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select('id', 'nama', 'telp', 'alamat', 'foto')
            ->with('ruangs:laboran_id,nama')
            ->first();

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
