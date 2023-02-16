<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\SubProdi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class PeminjamController extends Controller
{
    public function index(Request $request)
    {
        $subprodi_id = $request->get('subprodi_id');
        $keyword = $request->get('keyword');

        if ($subprodi_id != "" && $keyword != "") {
            $peminjams = User::where([
                ['role', 'peminjam'],
                ['nama', 'like', "%$keyword%"],
                ['subprodi_id', $subprodi_id]
            ])->orderBy('nama', 'ASC');
        } else if ($subprodi_id != "" && $keyword == "") {
            $peminjams = User::where([
                ['role', 'peminjam'],
                ['subprodi_id', $subprodi_id]
            ])->orderBy('nama', 'ASC');
        } else if ($subprodi_id == "" && $keyword != "") {
            $peminjams = User::where([
                ['role', 'peminjam'],
                ['nama', 'like', "%$keyword%"],
            ])->orderBy('nama', 'ASC');
        } else {
            $peminjams = User::where('role', 'peminjam')->orderBy('nama', 'ASC');
        }

        $subprodis = SubProdi::get();
        $users = $peminjams->paginate(10);

        return view('admin.peminjam.index', compact('users', 'subprodis'));
    }

    public function create()
    {
        $subprodis = SubProdi::get();
        return view('admin.peminjam.create', compact('subprodis'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'nama' => 'required',
            'subprodi_id' => 'required',
            'semester' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Lengkap tidak boleh kosong!',
            'subprodi_id.required' => 'Prodi harus dipilih!',
            'semester.required' => 'Semester harus dipilih!',
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
            $namafoto = null;
        }

        $user = User::create(array_merge($request->all(), [
            'kode' => $request->username,
            'password' => bcrypt($request->username),
            'role' => 'peminjam',
            'foto' => $namafoto
        ]));

        if ($user) {
            alert()->success('Success', 'Berhasil menambahkan Peminjam');
        } else {
            alert()->error('Error', 'Gagal menambahkan Peminjam!');
        }

        return redirect('admin/peminjam');
    }

    public function show($id)
    {
        $user = User::where('id', $id)->first();

        // return response($user);

        return view('admin.peminjam.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $subprodis = SubProdi::get();

        return view('admin.peminjam.edit', compact('user', 'subprodis'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|unique:users,username,' . $id . ',id|min:6',
            'nama' => 'required',
            'telp' => 'required|unique:users,telp,' . $id . ',id',
            'subprodi_id' => 'required',
            'semester' => 'required',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username user tidak boleh kosong!',
            'username.unique' => 'Username user sudah digunakan!',
            'nama.required' => 'Nama user tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'telp.min' => 'Nomor telepon minimal 10 karakter!',
            'subprodi_id.required' => 'Prodi harus dipilih!',
            'semester.required' => 'Semester harus dipilih!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
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

        $update = $user->update([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'subprodi_id' => $request->subprodi_id,
            'semester' => $request->semester,
            'alamat' => $request->alamat,
            'foto' => $namafoto
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui Peminjam');
        } else {
            alert()->error('Error', 'Gagal memperbarui Peminjam!');
        }

        return redirect('admin/peminjam');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $delete = $user->delete();

        if ($delete) {
            Storage::disk('local')->delete('public/uploads/' . $user->foto);
            alert()->success('Success', 'Berhasil menghapus Peminjam');
        } else {
            alert()->error('Error', 'Gagal menghapus Peminjam!');
        }

        return back();
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import_peminjam.xlsx');
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

        $import = new UsersImport('peminjam');
        $import->import($file);

        // dd($import->failures());

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        } else {
            alert()->success('Success', 'Berhasil menambahkan Peminjam');
        }

        return redirect('admin/peminjam');
    }

    public function exportpeminjam(Request $request){
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
