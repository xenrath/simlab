<?php

namespace App\Http\Controllers\Admin\Pengguna;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Prodi;
use App\Models\SubProdi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class LaboranController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'laboran')
            ->select('id', 'prodi_id', 'nama')
            ->with('prodi:id,singkatan')
            ->orderBy('prodi_id')
            ->get();

        return view('admin.pengguna.laboran.index', compact('users'));
    }

    public function create()
    {
        $prodis = Prodi::where([
            ['id', '!=', '5'],
            ['id', '!=', '6'],
        ])
            ->select('id', 'singkatan')
            ->get();

        return view('admin.pengguna.laboran.create', compact('prodis'));
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
            'nama.required' => 'Nama Laboran tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
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

        User::create([
            'kode' => $request->kode,
            'username' => $request->kode,
            'password' => bcrypt($request->kode),
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $namafoto,
            'role' => 'laboran'
        ]);

        alert()->success('Success', 'Berhasil menambahkan Laboran');

        return redirect('admin/pengguna/laboran');
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'nama',
                'telp',
                'alamat',
                'prodi_id'
            )
            ->with('prodi:id,singkatan')
            ->first();

        return view('admin.pengguna.laboran.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'prodi_id',
                'nama',
                'telp',
                'alamat'
            )->first();
        $prodis = Prodi::where([
            ['id', '!=', '5'],
            ['id', '!=', '6'],
        ])
            ->select('id', 'singkatan')
            ->get();

        return view('admin.pengguna.laboran.edit', compact('user', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'prodi_id' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'nama.required' => 'Nama Laboran tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Ukuran foto terlalu besar, max 2 MB!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $request->foto);
            $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
            $namafoto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
            $request->foto->storeAs('public/uploads/', $namafoto);
        } else {
            $namafoto = User::where('id', $id)->value('foto');
        }

        User::where('id', $id)->update([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $namafoto
        ]);

        alert()->success('Success', 'Berhasil memperbarui user');

        return redirect('admin/pengguna/laboran');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $delete = $user->delete();

        if ($delete) {
            Storage::disk('local')->delete('public/uploads/' . $user->foto);
            alert()->success('Success', 'Berhasil menghapus Laboran');
        } else {
            alert()->error('Error', 'Gagal menghapus Laboran!');
        }

        return back();
    }

    public function reset_password($id)
    {
        $kode = User::where('id', $id)->value('kode');

        User::where('id', $id)->update([
            'password' => bcrypt($kode)
        ]);

        alert()->success('Success', 'Berhasil mereset Password');

        return redirect('admin/pengguna/laboran');
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
