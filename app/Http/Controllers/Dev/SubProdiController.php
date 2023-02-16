<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\SubProdi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubProdiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subprodis = SubProdi::paginate(10);
        return view('dev.subprodi.index', compact('subprodis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subprodis = SubProdi::get();
        return view('dev.subprodi.create', compact('subprodis'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenjang' => 'required',
            'nama' => 'required',
            'prodi_id' => 'required',
        ], [
            'jenjang.required' => 'Jenjang harus dipilih!',
            'nama.required' => 'Nama prodi tidak boleh kosong!',
            'prodi_id.required' => 'Prodi harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        $prodi = SubProdi::create($request->all());

        if ($prodi) {
            alert()->success('Success', 'Berhasil menambahkan program studi');
        } else {
            alert()->error('Error', 'Gagal menambahkan program studi!');
        }

        return redirect('dev/prodi');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $prodi = SubProdi::findOrFail($id);

        return view('dev.subprodi.edit', compact('prodi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenjang' => 'required',
            'nama' => 'required',
        ], [
            'jenjang.required' => 'Jenjang harus dipilih!',
            'nama.required' => 'Nama program studi tidak boleh kosong!',
        ]);

        $prodi = SubProdi::where('id', $id)->update([
            'jenjang' => $request->jenjang,
            'nama' => $request->nama
        ]);

        if ($prodi) {
            alert()->success('Success', 'Berhasil memperbarui prodi');
        } else {
            alert()->error('Error', 'Gagal memperbarui prodi!');
        }

        return redirect('dev/prodi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $prodi = SubProdi::findOrFail($id);
        $prodi->delete();

        alert()->success('Success', 'Berhasil menghapus prodi');

        return redirect('dev/prodi');
    }
}
