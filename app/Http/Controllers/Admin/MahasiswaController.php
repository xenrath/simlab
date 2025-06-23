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

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');

        $users = User::whereNotNull('kode')
            ->where('role', 'peminjam')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('kode', 'like', "%$keyword%")
                        ->orWhere('nama', 'like', "%$keyword%");
                });
            })
            ->select(
                'id',
                'kode',
                'nama',
                'telp',
                'subprodi_id',
                'tingkat',
                'alamat',
            )
            ->with('subprodi:id,jenjang,nama')
            ->orderBy('subprodi_id')
            ->orderBy('kode')
            ->paginate(10);

        return view('admin.mahasiswa.index', compact('users'));
    }

    public function create()
    {
        $subprodis = SubProdi::select('id', 'jenjang', 'nama')
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();
        // 
        return view('admin.mahasiswa.create', compact('subprodis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode'         => 'required|unique:users',
            'nama'         => 'required',
            'subprodi_id'  => 'required',
            'tingkat'      => 'required',
            'telp'         => 'nullable|unique:users',
            'foto'         => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'kode.required'        => 'NIM tidak boleh kosong!',
            'kode.unique'          => 'NIM sudah digunakan!',
            'nama.required'        => 'Nama mahasiswa tidak boleh kosong!',
            'subprodi_id.required' => 'Prodi harus dipilih!',
            'tingkat.required'     => 'Tingkat harus dipilih!',
            'telp.unique'          => 'No. Telepon sudah digunakan!',
            'foto.image'           => 'Foto harus berformat jpeg, jpg, png!',
            'foto.max'             => 'Ukuran foto terlalu besar, max 2 MB!',
        ]);

        // Handle foto jika diunggah
        $foto = null;
        if ($request->hasFile('foto')) {
            $filename = $validated['kode'] . '_' . random_int(10, 99) . '.' . $request->file('foto')->getClientOriginalExtension();
            $foto = 'user/peminjam/' . $filename;
            $request->file('foto')->storeAs('public/uploads', $foto);
        }

        // Simpan data ke database
        User::create([
            'kode'        => $validated['kode'],
            'username'    => $validated['kode'],
            'password'    => bcrypt($validated['kode']),
            'nama'        => $validated['nama'],
            'subprodi_id' => $validated['subprodi_id'],
            'tingkat'     => $validated['tingkat'],
            'telp'        => $validated['telp'] ?? null,
            'alamat'      => $request->alamat,
            'foto'        => $foto,
            'role'        => 'peminjam'
        ]);

        alert()->success('Success', 'Berhasil menambahkan Mahasiswa');
        return redirect('admin/mahasiswa');
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
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

        return view('admin.mahasiswa.show', compact('user'));
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
            ->with('subprodi:id,nama,jenjang')
            ->findOrFail($id);
        $subprodis = SubProdi::select('id', 'nama', 'jenjang')->get();
        // 
        return view('admin.mahasiswa.edit', compact('user', 'subprodis'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'kode' => 'required|unique:users,kode,' . $id,
            'nama' => 'required',
            'subprodi_id' => 'required',
            'tingkat' => 'required',
            'telp' => 'nullable|unique:users,telp,' . $id,
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

        // Handle foto
        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::exists('public/uploads/' . $user->foto)) {
                Storage::delete('public/uploads/' . $user->foto);
            }
            $fotoName = 'user/peminjam/' . $request->kode . '_' . random_int(10, 99) . '.' . $request->foto->getClientOriginalExtension();
            $request->foto->storeAs('public/uploads', $fotoName);
        } else {
            $fotoName = $user->foto;
        }

        $user->update([
            'kode' => $validated['kode'],
            'nama' => $validated['nama'],
            'subprodi_id' => $validated['subprodi_id'],
            'tingkat' => $validated['tingkat'],
            'telp' => $validated['telp'] ?? null,
            'alamat' => $request->alamat,
            'foto' => $fotoName
        ]);

        alert()->success('Success', 'Berhasil memperbarui Mahasiswa');
        return redirect('admin/mahasiswa');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Hapus foto jika ada
        if ($user->foto && Storage::exists('public/uploads/' . $user->foto)) {
            Storage::delete('public/uploads/' . $user->foto);
        }

        $user->delete();

        alert()->success('Success', 'Berhasil menghapus Mahasiswa');
        return redirect()->back();
    }

    public function reset_password($id)
    {
        $user = User::select('id', 'username')->findOrFail($id);
        $user->update([
            'password' => bcrypt($user->username),
        ]);
        alert()->success('Success', 'Berhasil mereset Password');
        return redirect('admin/mahasiswa');
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new UsersImport, $request->file('file'));

        alert()->success('Success', 'Berhasil menambahkan User');

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

        return view('admin.mahasiswa.ubah_tingkat', compact('users', 'subprodis'));
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
