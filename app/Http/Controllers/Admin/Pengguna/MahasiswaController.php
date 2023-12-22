<?php

namespace App\Http\Controllers\Admin\Pengguna;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\SubProdi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        if ($keyword != "") {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam']
            ])->where(function ($query) use ($keyword) {
                $query->where('kode', 'like', "%$keyword%");
                $query->orWhere('nama', 'like', "%$keyword%");
            })
                ->select('id', 'kode', 'nama', 'subprodi_id')
                ->with('subprodi:id,jenjang,nama')
                ->orderBy('subprodi_id')
                ->orderBy('kode')
                ->paginate(10);
        } else {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam']
            ])
                ->select('id', 'kode', 'nama', 'subprodi_id')
                ->with('subprodi:id,jenjang,nama')
                ->orderBy('subprodi_id')
                ->orderBy('kode')
                ->paginate(10);
        }

        return view('admin.pengguna.mahasiswa.index', compact('users'));
    }

    public function create()
    {
        $subprodis = SubProdi::get();

        return view('admin.pengguna.mahasiswa.create', compact('subprodis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:users',
            'nama' => 'required',
            'subprodi_id' => 'required',
            'tingkat' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'NIM tidak boleh kosong!',
            'kode.unique' => 'NIM sudah digunakan!',
            'nama.required' => 'Nama mahasiswa tidak boleh kosong!',
            'subprodi_id.required' => 'Prodi harus dipilih!',
            'tingkat.required' => 'Tingkat harus dipilih!',
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
            'subprodi_id' => $request->subprodi_id,
            'tingkat' => $request->tingkat,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $namafoto,
            'role' => 'peminjam'
        ]);

        alert()->success('Success', 'Berhasil menambahkan Mahasiswa');

        return redirect('admin/pengguna/mahasiswa');
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'subprodi_id',
                'kode',
                'nama',
                'telp',
                'alamat',
                'is_active',
                'tingkat',
                'foto'
            )
            ->with('subprodi:id,nama,jenjang')
            ->first();

        return view('admin.pengguna.mahasiswa.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'subprodi_id',
                'kode',
                'nama',
                'telp',
                'alamat',
                'tingkat',
                'foto'
            )
            ->first();
        $subprodis = SubProdi::select('id', 'nama', 'jenjang')->get();

        return view('admin.pengguna.mahasiswa.edit', compact('user', 'subprodis'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:users,kode,' . $id . ',id',
            'nama' => 'required',
            'subprodi_id' => 'required',
            'tingkat' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required' => 'NIM tidak boleh kosong!',
            'nama.required' => 'Nama mahasiswa tidak boleh kosong!',
            'subprodi_id.required' => 'Prodi harus dipilih!',
            'tingkat.required' => 'Tingkat harus dipilih!',
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
            'kode' => $request->kode,
            'subprodi_id' => $request->subprodi_id,
            'tingkat' => $request->tingkat,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $namafoto
        ]);

        alert()->success('Success', 'Berhasil memperbarui mahasiswa');

        return redirect('admin/pengguna/mahasiswa');
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
        return view('admin.pengguna.mahasiswa.profile', compact('user'));
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

    public function ubah_tingkat(Request $request)
    {
        $subprodi_id = $request->filter_subprodi_id;
        $tingkat = $request->filter_tingkat;

        if (is_null($subprodi_id) && is_null($tingkat)) {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam']
            ])
                ->select('id', 'subprodi_id', 'kode', 'nama', 'tingkat')
                ->with('subprodi:id,nama,jenjang')
                ->orderByDesc('kode')
                ->get();
        } elseif (!is_null($subprodi_id) && is_null($tingkat)) {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam'],
                ['subprodi_id', $subprodi_id]
            ])
                ->select('id', 'subprodi_id', 'kode', 'nama', 'tingkat')
                ->with('subprodi:id,nama,jenjang')
                ->orderByDesc('kode')
                ->get();
        } elseif (is_null($subprodi_id) && !is_null($tingkat)) {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam'],
                ['tingkat', $tingkat]
            ])
                ->select('id', 'subprodi_id', 'kode', 'nama', 'tingkat')
                ->with('subprodi:id,nama,jenjang')
                ->orderByDesc('kode')
                ->get();
        } elseif (!is_null($subprodi_id) && !is_null($tingkat)) {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam'],
                ['tingkat', $tingkat],
                ['subprodi_id', $subprodi_id],
            ])
                ->select('id', 'subprodi_id', 'kode', 'nama', 'tingkat')
                ->with('subprodi:id,nama,jenjang')
                ->orderByDesc('kode')
                ->get();
        }
        $subprodis = SubProdi::select('id', 'jenjang', 'nama')->get();

        return view('admin.pengguna.mahasiswa.ubah_tingkat', compact('users', 'subprodis'));
    }

    public function ubah_tingkat_proses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tingkat' => 'required',
            'user_id' => 'required',
        ], [
            'tingkat.required' => 'Tingkat belum dipilih!',
            'user_id.required' => 'Mahasiswa belum dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            alert()->error('Error', $error[0]);

            return back()->withInput();
        }

        foreach ($request->user_id as $user_id) {
            User::where('id', $user_id)->update([
                'tingkat' => $request->tingkat
            ]);
        }

        alert()->success('Success', 'Berhasil mengubah Tingkat');

        return back();
    }
}
