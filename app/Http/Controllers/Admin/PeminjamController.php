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
        if ($request->kategori == '1') {
            $validator = Validator::make($request->all(), [
                'username_1' => 'required|unique:users,username',
                'nama_1' => 'required',
                'subprodi_id_1' => 'required',
                'semester_1' => 'required',
                'telp_1' => 'nullable|unique:users',
                'foto_1' => 'image|mimes:jpeg,jpg,png|max:2048',
            ], [
                'username_1.required' => 'Username tidak boleh kosong!',
                'username_1.unique' => 'Username sudah digunakan!',
                'nama_1.required' => 'Nama lengkap tidak boleh kosong!',
                'subprodi_id_1.required' => 'Prodi harus dipilih!',
                'semester_1.required' => 'Semester harus dipilih!',
                'telp_1.unique' => 'Nomor telepon sudah digunakan!',
                'foto_1.image' => 'Foto harus berformat jpeg, jpg, png!',
            ]);
        } elseif ($request->kategori == '2') {
            $validator = Validator::make($request->all(), [
                'nama_2' => 'required',
                'telp_2' => 'required|unique:users',
                'alamat_2' => 'required',
            ], [
                'nama_2.required' => 'Nama instansi tidak boleh kosong!',
                'telp_2.required' => 'Nomor tidak boleh kosong!',
                'telp_2.unique' => 'Nomor sudah digunakan!',
                'alamat_2.required' => 'Alamat instansi harus tidak boleh kosong!',
            ]);
        }

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        if ($request->kategori == '1') {
            $kode = $request->username_1;
            $username = $request->username_1;
            $nama = $request->nama_1;
            $subprodi = $request->subprodi_1;
            $semester = $request->semester_1;
            $telp = $request->telp_1;
            $alamat = $request->alamat_1;
            $gender = 'L';
            $password = bcrypt($request->username_1);
            if ($request->foto) {
                $foto = str_replace(' ', '', $request->foto->getClientOriginalName());
                $foto = 'user/' . date('mYdHs') . rand(1, 10) . '_' . $foto;
                $request->foto->storeAs('public/uploads/', $foto);
            } else {
                $foto = null;
            }
        } elseif ($request->kategori == '2') {
            $kode = null;
            $username = "+62" + $request->telp_2;
            $nama = $request->nama_2;
            $telp = $request->telp_2;
            $alamat = $request->alamat_2;
            $gender = 'L';
            $password = bcrypt('simlabBHAMADA');
        }

        $user = User::create([
            'kode' => $kode,
            'username' => $username,
            'nama' => $nama,
            'subprodi' => $subprodi,
            'semester' => $semester,
            'telp' => $telp,
            'alamat' => $alamat,
            'gender' => $gender,
            'password' => $password,
            'foto' => $foto,
        ]);

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

    public function exportpeminjam(Request $request)
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
