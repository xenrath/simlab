<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Models\MainProdi;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Tempat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $prodi_id = $request->get('prodi_id');
        $keyword = $request->get('keyword');

        if ($prodi_id != "" && $keyword != "") {
            $ruangs = Ruang::where('prodi_id', $prodi_id)->where('nama', 'like', "%$keyword%")
                ->orWhereHas('laboran', function ($query) use ($keyword, $prodi_id) {
                    $query->whereHas('ruangs', function ($query) use ($prodi_id) {
                        $query->where('prodi_id', $prodi_id);
                    })->where('nama', 'like', "%$keyword%");
                })->orderBy('kode', 'ASC')->paginate(10);
        } elseif ($prodi_id != "" && $keyword == "") {
            $ruangs = Ruang::where('prodi_id', $prodi_id)->orderBy('kode', 'ASC')->paginate(10);
        } elseif ($prodi_id == "" && $keyword != "") {
            $ruangs = Ruang::where('nama', 'like', "%$keyword%")->orWhereHas('laboran', function ($query) use ($keyword) {
                $query->where('nama', 'like', "%$keyword%");
            })->orderBy('prodi_id', 'ASC')->orderBy('kode', 'ASC')->paginate(10);
        } else {
            $ruangs = Ruang::orderBy('kode', 'ASC')->paginate(10);
        }

        $prodis = Prodi::where([
            ['kode', '!=', 'LC'],
            ['kode', '!=', 'GB']
        ])->get();

        return view('dev.ruang.index', compact('ruangs', 'prodis'));
    }

    public function create()
    {
        $users = User::where('role', 'laboran')->orderBy('nama', 'ASC')->get();
        $tempats = Tempat::get();
        $prodis = Prodi::get();
        $kode = $this->generateCode();

        return view('dev.ruang.create', compact('users', 'tempats', 'prodis', 'kode'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:ruangs',
            'nama' => 'required',
            'tempat_id' => 'required',
            'lantai' => 'required|in:L1,L2',
            'prodi_id' => 'required',
            'laboran_id' => 'required'
        ], [
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama ruangan harus diisi!',
            'tempat_id.required' => 'Main prodi harus dipilih!',
            'lantai.required' => 'Lantai harus dipilih!',
            'lantai.in' => 'Lantai yang dimasukan salah!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Ruang::create($request->all());

        alert()->success('Success', 'Berhasil menambahkan Ruangan');

        return redirect('dev/ruang');
    }

    public function edit($id)
    {
        $ruang = Ruang::find($id);
        $tempats = Tempat::get();
        $prodis = Prodi::get();
        $laborans = User::where('role', 'laboran')->orderBy('nama', 'ASC')->get();

        return view('dev.ruang.edit', compact('ruang', 'tempats', 'prodis', 'laborans'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:ruangs',
            'nama' => 'required',
            'tempat_id' => 'required',
            'lantai' => 'required|in:L1,L2',
            'prodi_id' => 'required',
            'laboran_id' => 'required'
        ], [
            'kode.required' => 'Kode harus diisi!',
            'kode.unique' => 'Kode sudah digunakan!',
            'nama.required' => 'Nama ruangan harus diisi!',
            'tempat_id.required' => 'Main prodi harus dipilih!',
            'lantai.required' => 'Lantai harus dipilih!',
            'lantai.in' => 'Lantai yang dimasukan salah!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        Ruang::where('id', $id)->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'lantai' => $request->lantai,
            'prodi_id' => $request->prodi_id,
            'laboran_id' => $request->laboran_id
        ]);

        alert()->success('Success', 'Berhasil memperbarui Ruangan');

        return redirect('dev/ruang');
    }

    public function destroy($id)
    {
        $ruang = Ruang::where('id', $id)->first();
        $ruang->delete();

        alert()->success('Success', 'Berhasil menghapus Ruangan');

        return back();
    }

    public function prodi($id)
    {
        $prodis = Prodi::whereHas('mainprodi', function ($query) use ($id) {
            $query->where('tempat_id', $id);
        })->get();

        return json_encode($prodis);
    }

    public function generateCode()
    {
        $ruangs = Ruang::get();

        if (count($ruangs) > 0) {
            $jumlah = count($ruangs) + 1;
            $kode = sprintf('%02s', $jumlah);
        } else {
            $kode = "01";
        }

        return $kode;
    }
}
