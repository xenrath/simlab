<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuangController extends Controller
{
    // Kebidanan

    public function index(Request $request)
    {
        $prodi = $request->get('prodi');
        $keyword = $request->get('keyword');

        if ($prodi != "" && $keyword != "") {
            $ruangs = Ruang::where('prodi', $prodi)
                ->where('nama', 'like', "%$keyword%")
                ->orWhereHas('user', function ($query) use ($keyword, $prodi) {
                    $query->whereHas('ruangs', function ($query) use ($prodi) {
                        $query->where('prodi', $prodi);
                    })->where('nama', 'like', "%$keyword%");
                })->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($prodi != "" && $keyword == "") {
            $ruangs = Ruang::where('prodi', $prodi)->orderBy('nama', 'ASC')->paginate(10);
        } elseif ($prodi == "" && $keyword != "") {
            $ruangs = Ruang::where('nama', 'like', "%$keyword%")->orWhereHas('laboran', function ($query) use ($keyword) {
                $query->where('nama', 'like', "%$keyword%");
            })->orderBy('prodi', 'ASC')->orderBy('nama', 'ASC')->paginate(10);
        } else {
            $ruangs = Ruang::orderBy('nama', 'ASC')->paginate(10);
        }

        // $ko = Ruang::orderBy('prodi_id', 'ASC')->orderBy('nama', 'ASC')->with('laborans')->get();
        // return response($ko);

        $prodis = Prodi::get();

        return view('admin.ruang.index', compact('ruangs', 'prodis'));
    }

    public function create()
    {
        $users = User::where('role', 'laboran')->orderBy('nama', 'ASC')->get();

        return view('admin.ruang.create', compact('prodis', 'users'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'prodi' => 'required',
            'laboran_id' => 'required'
        ], [
            'nama.required' => 'Nama ruangan harus diisi!',
            'prodi.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Ruang::create($request->all());

        alert()->success('Success', 'Berhasil menambahkan Ruangan');

        return redirect('admin/ruang');
    }

    public function edit($id)
    {
        $ruang = Ruang::find($id);
        $users = User::where('role', 'laboran')->orderBy('nama', 'ASC')->get();

        return view('admin.ruang.edit', compact('ruang', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'prodi' => 'required',
            'laboran_id' => 'required',
        ], [
            'nama.required' => 'Nama ruangan harus diisi!',
            'prodi.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Ruang::where('id', $id)->update([
            'nama' => $request->nama,
            'prodi' => $request->prodi,
            'laboran_id' => $request->laboran_id
        ]);

        alert()->success('Success', 'Berhasil memperbarui Ruangan');

        return redirect('admin/ruang');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ruang  $ruang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ruang $ruang)
    {
        $hapus = $ruang->delete();
        if ($hapus) {
            alert()->success('Success', 'Berhasil menghapus ruangan');
        } else {
            alert()->error('Error', 'Gagal menghapus ruangan!');
        }

        return redirect('admin/ruang');
    }
}
