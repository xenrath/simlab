<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\SubProdi;
use App\Models\User;
use Illuminate\Http\Request;

class PeminjamController extends Controller
{
    public function index(Request $request)
    {
        $subprodi_id = $request->get('subprodi_id');
        $keyword = $request->get('keyword');

        if ($subprodi_id == 'lainnya' && $keyword != "") {
            $users = User::where([
                ['role', 'peminjam'],
                ['kode', null],
            ])->paginate(10);
        } else if ($subprodi_id == 'lainnya' && $keyword == "") {
            $users = User::where([
                ['role', 'peminjam'],
                ['kode', null],
            ])->paginate(10);
        } else if ($subprodi_id != "" && $keyword != "") {
            $users = User::where([
                ['role', 'peminjam'],
                ['nama', 'like', "%$keyword%"],
                ['subprodi_id', $subprodi_id]
            ])->orWhere([
                ['role', 'peminjam'],
                ['kode', 'like', "%$keyword%"],
                ['subprodi_id', $subprodi_id]
            ])->orderBy('kode', 'DESC')->paginate(10);
        } else if ($subprodi_id != "" && $keyword == "") {
            $users = User::where([
                ['role', 'peminjam'],
                ['subprodi_id', $subprodi_id]
            ])->orderBy('kode', 'DESC')->paginate(10);
        } else if ($subprodi_id == "" && $keyword != "") {
            $users = User::where([
                ['role', 'peminjam'],
                ['nama', 'like', "%$keyword%"],
            ])->orWhere([
                ['role', 'peminjam'],
                ['kode', 'like', "%$keyword%"],
            ])->orderBy('kode', 'DESC')->paginate(10);
        } else {
            $users = User::where('role', 'peminjam')->orderBy('kode', 'DESC')->paginate(10);
        }

        $subprodis = SubProdi::get();

        return view('kalab.peminjam.index', compact('users', 'subprodis'));
    }

    // public function create()
    // {
    //     $prodis = Prodi::get();
    //     return view('kalab.peminjam.create', compact('prodis'));
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
    //         $namafoto = '';
    //     }

    //     $user = User::create(array_merge($request->all(), [
    //         'kode' => $request->username,
    //         'password' => bcrypt($request->username),
    //         'role' => 'peminjam',
    //         'foto' => $namafoto
    //     ]));

    //     if ($user) {
    //         alert()->success('Success', 'Berhasil menambahkan Peminjam');
    //     } else {
    //         alert()->error('Error', 'Gagal menambahkan Peminjam!');
    //     }

    //     return redirect('kalab/peminjam');
    // }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        return view('kalab.peminjam.show', compact('user'));
    }

    // public function edit($id)
    // {
    //     $user = User::find($id);
    //     $prodis = Prodi::get();

    //     return view('kalab.peminjam.edit', compact('user', 'prodis'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'username' => 'required|unique:users,username,' . $id . ',id|min:6',
    //         'nama' => 'required',
    //         'telp' => 'required|unique:users,telp,' . $id . ',id',
    //         'prodi_id' => 'required',
    //         'semester' => 'required',
    //         'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
    //     ], [
    //         'username.required' => 'Username user tidak boleh kosong!',
    //         'username.unique' => 'Username user sudah digunakan!',
    //         'nama.required' => 'Nama user tidak boleh kosong!',
    //         'telp.unique' => 'No. Telepon sudah digunakan!',
    //         'telp.min' => 'Nomor telepon minimal 10 karakter!',
    //         'prodi_id.required' => 'Prodi harus dipilih!',
    //         'semester.required' => 'Semester harus dipilih!',
    //         'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
    //     ]);

    //     $user = User::find($id);

    //     if ($request->foto) {
    //         Storage::disk('local')->delete('public/uploads/' . $request->foto);
    //         $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
    //         $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
    //         $request->foto->storeAs('public/uploads/', $namafoto);
    //     } else {
    //         $namafoto = $user->foto;
    //     }

    //     $update = $user->update([
    //         'kode' => $request->username,
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'telp' => $request->telp,
    //         'prodi_id' => $request->prodi_id,
    //         'semester' => $request->semester,
    //         'alamat' => $request->alamat,
    //         'foto' => $namafoto
    //     ]);

    //     if ($update) {
    //         alert()->success('Success', 'Berhasil memperbarui Peminjam');
    //     } else {
    //         alert()->error('Error', 'Gagal memperbarui Peminjam!');
    //     }

    //     return redirect('kalab/peminjam');
    // }

    // public function destroy($id)
    // {
    //     $user = User::find($id);
    //     $delete = $user->delete();

    //     if ($delete) {
    //         Storage::disk('local')->delete('public/uploads/' . $user->foto);
    //         alert()->success('Success', 'Berhasil menghapus Peminjam');
    //     } else {
    //         alert()->error('Error', 'Gagal menghapus Peminjam!');
    //     }

    //     return back();
    // }
}
