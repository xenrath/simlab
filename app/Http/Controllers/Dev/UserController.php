<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\Kalab;
use App\Models\Laboran;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\SubProdi;
use App\Models\Tamu;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $keyword = $request->get('keyword');

        if ($filter == 'role') {
            $data = User::where('role', '!=', 'dev')->orderBy('role', 'asc');
        } elseif ($filter == 'updated_at') {
            $data = User::where('role', '!=', 'dev')->orderBy('updated_at', 'desc');
        } else {
            $data = User::where('role', '!=', 'dev');
        }

        if ($keyword != "") {
            $users = $data->where('nama', 'like', "%$keyword%")
                ->orWhere('kode', 'like', "%$keyword%")
                ->paginate(10);
        } else {
            $users = $data->paginate(10);
        }

        $subprodis = SubProdi::all();

        return view('dev.user.index', compact('users', 'subprodis'));
    }

    public function create()
    {
        if (request()->get('role') == 'admin') {
            return view('dev.user.create_admin');
        } elseif (request()->get('role') == 'kalab') {
            return view('dev.user.create_kalab');
        } elseif (request()->get('role') == 'laboran') {
            $prodis = Prodi::select('id', 'singkatan')
                ->where('is_prodi', true)
                ->get();
            return view('dev.user.create_laboran', compact('prodis'));
        } elseif (request()->get('role') == 'peminjam') {
            $subprodis = SubProdi::select('id', 'jenjang', 'nama')->get();
            return view('dev.user.create_peminjam', compact('subprodis'));
        } else {
            return back()->with('error', array('Role belum dipilih!'));
        }

        return view('dev.user.create', compact('subprodis'));
    }

    public function store(Request $request)
    {
        if ($request->role == 'admin') {
            return $this->store_admin($request);
        } elseif ($request->role == 'kalab') {
            return $this->store_kalab($request);
        } elseif ($request->role == 'laboran') {
            return $this->store_laboran($request);
        } elseif ($request->role == 'peminjam') {
            return $this->store_peminjam($request);
        } else {
            alert()->error('Error', 'Gagal menyimpan data!');
            return back();
        }
    }

    public function store_admin($request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'username' => 'required|unique:users',
            'nama' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'role.required' => 'Role tidak boleh kosong!',
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Admin tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.mimes' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Foto maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        if ($request->foto) {
            $foto = 'user/admin/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = null;
        }

        User::create([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'role' => 'admin'
        ]);

        alert()->success('Success', 'Berhasil menambahkan Admin');

        return redirect('dev/user');
    }

    public function store_kalab($request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'username' => 'required|unique:users',
            'nama' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'role.required' => 'Role tidak boleh kosong!',
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Kalab tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.mimes' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Foto maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        if ($request->foto) {
            $foto = 'user/kalab/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = null;
        }

        User::create([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'role' => 'kalab'
        ]);

        alert()->success('Success', 'Berhasil menambahkan Kalab');

        return redirect('dev/user');
    }

    public function store_laboran($request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'username' => 'required|unique:users',
            'nama' => 'required',
            'prodi_id' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'role.required' => 'Role tidak boleh kosong!',
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Laboran tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.mimes' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Foto maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        if ($request->foto) {
            $foto = 'user/laboran/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = null;
        }

        User::create([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'role' => 'laboran',
            'prodi_id' => $request->prodi_id,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Laboran');

        return redirect('dev/user');
    }

    public function store_peminjam($request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
            'username' => 'required|unique:users',
            'nama' => 'required',
            'subprodi_id' => 'required',
            'tingkat' => 'required',
            'telp' => 'nullable|unique:users',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'role.required' => 'Role tidak boleh kosong!',
            'username.required' => 'NIM tidak boleh kosong!',
            'username.unique' => 'NIM sudah digunakan!',
            'nama.required' => 'Nama Mahasiswa tidak boleh kosong!',
            'subprodi_id.required' => 'Prodi harus dipilih!',
            'tingkat.required' => 'Tingkat harus dipilih!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.mimes' => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max' => 'Foto maksimal ukuran 2MB!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        if ($request->foto) {
            $foto = 'user/peminjam/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = null;
        }

        User::create([
            'kode' => $request->username,
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'role' => 'peminjam',
            'subprodi_id' => $request->subprodi_id,
            'tingkat' => $request->tingkat,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Mahasiswa');

        return redirect('dev/user');
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'username',
                'nama',
                'telp',
                'alamat',
                'foto',
                'role',
                'subprodi_id',
                'tingkat',
                'prodi_id',
            )
            ->with('subprodi:id,nama,jenjang', 'prodi:id,singkatan')
            ->first();

        return view('dev.user.show', compact('user'));
    }

    public function edit($id)
    {
        $role = User::where('id', $id)->value('role');
        if ($role == 'admin') {
            $user = User::where('id', $id)
                ->select(
                    'id',
                    'username',
                    'nama',
                    'telp',
                    'alamat',
                    'foto',
                )
                ->first();
            return view('dev.user.edit_admin', compact('user'));
        } elseif ($role == 'kalab') {
            $user = User::where('id', $id)
                ->select(
                    'id',
                    'username',
                    'nama',
                    'telp',
                    'alamat',
                    'foto',
                )
                ->first();
            return view('dev.user.edit_kalab', compact('user'));
        } elseif ($role == 'laboran') {
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
                ->with('prodi:id,nama')
                ->first();
            $prodis = Prodi::select('id', 'singkatan')
                ->where('is_prodi', true)
                ->get();
            return view('dev.user.edit_laboran', compact('user', 'prodis'));
        } elseif ($role == 'peminjam') {
            $user = User::where('id', $id)
                ->select(
                    'id',
                    'kode',
                    'username',
                    'nama',
                    'telp',
                    'alamat',
                    'foto',
                    'subprodi_id',
                    'tingkat',
                )
                ->with('subprodi:id,nama,jenjang')
                ->first();
            $subprodis = SubProdi::select('id', 'jenjang', 'nama')->get();
            return view('dev.user.edit_peminjam', compact('user', 'subprodis'));
        } else {
            alert()->error('Error', 'Gagal menampilkan Data!');
            return back();
        }
    }

    public function update(Request $request, $id)
    {
        $role = User::where('id', $id)->value('role');
        if ($role == 'admin') {
            return $this->update_admin($request, $id);
        } elseif ($role == 'kalab') {
            return $this->update_kalab($request, $id);
        } elseif ($role == 'laboran') {
            return $this->update_laboran($request, $id);
        } elseif ($role == 'peminjam') {
            return $this->update_peminjam($request, $id);
        } else {
            alert()->error('Error', 'Gagal menyimpan Data!');
            return back();
        }
    }

    public function update_admin($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $id . ',id',
            'nama' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Admin tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $request->foto);
            $foto = 'user/admin/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = User::where('id', $id)->value('foto');
        }

        User::where('id', $id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Admin');

        return redirect('dev/user');
    }

    public function update_kalab($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username,' . $id . ',id',
            'nama' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id . ',id',
            'foto' => 'image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'username.required' => 'Username tidak boleh kosong!',
            'username.unique' => 'Username sudah digunakan!',
            'nama.required' => 'Nama Admin tidak boleh kosong!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $request->foto);
            $foto = 'user/kalab/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = User::where('id', $id)->value('foto');
        }

        User::where('id', $id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Kalab');

        return redirect('dev/user');
    }

    public function update_laboran($request, $id)
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
            'nama.required' => 'Nama Admin tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'telp.unique' => 'No. Telepon sudah digunakan!',
            'foto.image' => 'Foto harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with('error', $validator->errors()->all());
        }

        $user = User::where('id', $id)->select('foto')->first();

        if ($request->foto) {
            Storage::disk('local')->delete('public/uploads/' . $user->foto);
            $foto = 'user/laboran/' . $request->username . '_' . random_int(10, 99) . '.' . $request->gambar->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads/', $foto);
        } else {
            $foto = $user->foto;
        }

        User::where('id', $id)->update([
            'username' => $request->username,
            'nama' => $request->nama,
            'telp' => $request->telp,
            'alamat' => $request->alamat,
            'foto' => $foto,
            'prodi_id' => $request->prodi_id,
        ]);

        alert()->success('Success', 'Berhasil memperbarui Laboran');

        return redirect('dev/user');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        Storage::disk('local')->delete('public/uploads/' . $user->foto);
        $user->delete();

        alert()->success('Success', 'Berhasil menghapus User');

        return back();
    }

    public function trash()
    {
        $users = User::onlyTrashed()->select('id', 'nama', 'role')->paginate(10);
        return view('dev.user.trash', compact('users'));
    }
    
    public function trash_show($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->first();
        return view('dev.user.trash_show', compact('user'));
    }

    public function restore($id = null)
    {
        if ($id != null) {
            User::where('id', $id)->onlyTrashed()->restore();
        } else {
            User::onlyTrashed()->restore();
        }

        alert()->success('Success', 'Berhasil memulihkan User');

        return back();
    }

    public function delete($id = null)
    {
        if ($id != null) {
            User::where('id', $id)->onlyTrashed()->forceDelete();
        } else {
            User::onlyTrashed()->forceDelete();
        }

        alert()->success('Success', 'Berhasil menghapus User');

        return back();
    }

    public function export()
    {
        $file = public_path('storage/uploads/file/format_import.xlsx');
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

        $import = new UsersImport();
        $import->import($file);

        // dd($import->failures());

        if ($import->failures()->isNotEmpty()) {
            return back()->withFailures($import->failures());
        } else {
            alert()->success('Success', 'Berhasil menambahkan Peminjam');
        }

        return redirect('dev/user');
    }

    public function aktivasi(Request $request)
    {
        $subprodi = SubProdi::where('id', $request->subprodi_id)->first();
        $tahun = substr(Carbon::now()->subYears($subprodi->lama)->format('Y'), -2);

        // $users = User::select('id', 'kode', 'status', DB::raw('MID(kode, 4, 2) as tahun'))
        //     ->where([
        //         ['role', 'peminjam'],
        //         ['subprodi_id', $subprodi->id],
        //     ])->get()->where('tahun', substr($tahun, -2));

        $users = User::withTrashed()->whereRaw(DB::raw('substr(kode, 4, 2) = ' . $tahun))->where([
            ['role', 'peminjam'],
            ['subprodi_id', $subprodi->id],
        ])->get();

        foreach ($users as $user) {
            User::where('id', $user->id)->update([
                'status' => false
            ]);
        }
    }

    public function detail()
    {
        $users = User::get();

        foreach ($users as $user) {
            if ($user->role == 'laboran') {
            }
        }
    }

    public function refresh_user()
    {
        $users = User::get();

        foreach ($users as $user) {
            $role = $user->role;
            if ($role == 'kalab') {
                $kalab = Kalab::where('user_id', $user->id)->get();
                if (is_null($kalab)) {
                    Kalab::create([
                        'user_id' => $user->id,
                        'nipy' => $user->kode,
                    ]);
                }
            } elseif ($role == 'laboran') {
                $laboran = Laboran::where('user_id', $user->id)->get();
                if (is_null($laboran)) {
                    Laboran::create([
                        'user_id' => $user->id,
                        'nipy' => $user->kode,
                        'telp' => $user->telp,
                        'alamat' => $user->alamat
                    ]);
                }
            } elseif ($role == 'peminjam' && $user->kode != null) {
                $mahasiswa = Mahasiswa::where('user_id', $user->id)->get();
                if (is_null($mahasiswa)) {
                    Mahasiswa::create([
                        'user_id' => $user->id,
                        'subprodi_id' => $user->subprodi_id,
                        'nim' => $user->kode,
                        'telp' => $user->telp,
                        'alamat' => $user->alamat,
                        'foto' => $user->foto,
                    ]);
                }
            } elseif ($role == 'peminjam' && $user->kode == null) {
                Tamu::create([
                    'nama' => $user->nama,
                    'telp' => $user->telp,
                    'institusi' => $user->alamat,
                    'alamat' => null,
                    'keperluan' => null
                ]);
            }
        }

        alert()->success('Success', 'Berhasil merefresh user');

        return back();
    }

    public function reset_password($id)
    {
        $username = User::where('id', $id)->value('username');
        User::where('id', $id)->update([
            'password' => bcrypt($username)
        ]);

        alert()->success('Success', 'Berhasil mereset password');
        return redirect('dev/user');
    }
}
