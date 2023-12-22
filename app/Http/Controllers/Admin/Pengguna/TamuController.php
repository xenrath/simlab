<?php

namespace App\Http\Controllers\Admin\Pengguna;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\SubProdi;
use App\Models\Tamu;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class TamuController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword != "") {
            $tamus = Tamu::where('nama', 'like', "%$keyword%")
                ->select('id', 'nama', 'institusi')
                ->orderBy('nama')
                ->paginate(10);
        } else {
            $tamus = Tamu::select('id', 'nama', 'institusi')
                ->orderBy('nama')
                ->paginate(10);
        }


        return view('admin.pengguna.tamu.index', compact('tamus'));
    }

    public function create()
    {
        return view('admin.pengguna.tamu.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'institusi' => 'required',
            'telp' => 'required|unique:tamus',
        ], [
            'nama.required' => 'Nama Tamu tidak boleh kosong!',
            'institusi.required' => 'Asal Institusi tidak boleh kosong!',
            'telp.required' => 'Nomor Telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Tamu::create([
            'nama' => $request->nama,
            'institusi' => $request->institusi,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Tamu');

        return redirect('admin/pengguna/tamu');
    }

    public function show($id)
    {
        $tamu = Tamu::where('id', $id)
            ->select(
                'nama',
                'institusi',
                'telp',
                'alamat'
            )
            ->first();

        return view('admin.pengguna.tamu.show', compact('tamu'));
    }

    public function edit($id)
    {
        $tamu = Tamu::where('id', $id)
            ->select(
                'id',
                'nama',
                'institusi',
                'telp',
                'alamat'
            )
            ->first();

        return view('admin.pengguna.tamu.edit', compact('tamu'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'institusi' => 'required',
            'telp' => 'required|unique:tamus,telp,' . $id . ',id',
        ], [
            'nama.required' => 'Nama Tamu tidak boleh kosong!',
            'institusi.required' => 'Asal Institusi tidak boleh kosong!',
            'telp.required' => 'Nomor Telepon tidak boleh kosong!',
            'telp.unique' => 'Nomor Telepon sudah digunakan!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Tamu::where('id', $id)->update([
            'nama' => $request->nama,
            'institusi' => $request->institusi,
            'telp' => $request->telp,
            'alamat' => $request->alamat
        ]);

        alert()->success('Success', 'Berhasil memperbarui Tamu');
        
        return redirect('admin/pengguna/tamu');
    }

    public function destroy($id)
    {
        $tamu = Tamu::find($id);
        $tamu->delete();

        alert()->success('Success', 'Berhasil menghapus Tamu');

        return back();
    }

    public function profile()
    {
        $id = auth()->user()->id;
        $user = User::find($id);
        return view('admin.pengguna.tamu.profile', compact('user'));
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
