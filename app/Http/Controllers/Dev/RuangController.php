<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
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

        if ($prodi_id) {
            $ruangs = Ruang::where('prodi_id', $prodi_id)
                ->select(
                    'id',
                    'prodi_id',
                    'laboran_id',
                    'nama',
                    'is_praktik',
                )
                ->with('prodi:id,singkatan', 'laboran:id,nama')
                ->orderBy('kode', 'ASC')
                ->paginate(10);
        } else {
            $ruangs = Ruang::select(
                'id',
                'prodi_id',
                'laboran_id',
                'nama',
                'is_praktik',
            )
                ->orderBy('kode', 'ASC')
                ->with('prodi:id,singkatan', 'laboran:id,nama')
                ->paginate(10);
        }

        $prodis = Prodi::where('is_prodi', true)
            ->select('id', 'singkatan')
            ->get();

        return view('dev.ruang.index', compact('ruangs', 'prodis'));
    }

    public function create()
    {
        if (request()->get('is_praktik')) {
            return $this->create_is_praktik_true();
        } else {
            return $this->create_is_praktik_false();
        }
    }

    public function create_is_praktik_true()
    {
        $users = User::where('role', 'laboran')
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,singkatan')
            ->get();
        $tempats = Tempat::select('id', 'nama')->get();
        $prodis = Prodi::where('is_prodi', true)->select('id', 'singkatan')->get();

        return view('dev.ruang.create', compact('users', 'tempats', 'prodis'));
    }

    public function store(Request $request)
    {
        if ($request->is_praktik) {
            $validator = Validator::make($request->all(), [
                'kode' => 'required|unique:ruangs',
                'nama' => 'required',
                'tempat_id' => 'required',
                'lantai' => 'required',
                'prodi_id' => 'required',
                'laboran_id' => 'required'
            ], [
                'kode.required' => 'Kode harus diisi!',
                'kode.unique' => 'Kode sudah digunakan!',
                'nama.required' => 'Nama ruangan harus diisi!',
                'tempat_id.required' => 'Tempat harus dipilih!',
                'lantai.required' => 'Lantai harus dipilih!',
                'prodi_id.required' => 'Prodi harus dipilih!',
                'laboran_id.required' => 'Laboran harus dipilih!',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'kode' => 'required|unique:ruangs',
                'nama' => 'required',
                'tempat_id' => 'required',
                'lantai' => 'required',
                'prodi_id' => 'required',
            ], [
                'kode.required' => 'Kode harus diisi!',
                'kode.unique' => 'Kode sudah digunakan!',
                'nama.required' => 'Nama ruangan harus diisi!',
                'tempat_id.required' => 'Tempat harus dipilih!',
                'lantai.required' => 'Lantai harus dipilih!',
                'prodi_id.required' => 'Prodi harus dipilih!',
            ]);
        }

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        if ($request->is_praktik) {
            $laboran_id = $request->laboran_id;
        } else {
            $laboran_id = null;
        }

        Ruang::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'lantai' => $request->lantai,
            'prodi_id' => $request->prodi_id,
            'is_praktik' => $request->is_praktik,
            'laboran_id' => $laboran_id,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Ruangan');

        return redirect('dev/ruang');
    }

    public function edit($id)
    {
        $ruang = Ruang::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'tempat_id',
                'lantai',
                'prodi_id',
                'is_praktik',
                'laboran_id'
            )
            ->first();
        $tempats = Tempat::select('id', 'nama')->get();
        $prodis = Prodi::where([
            ['id', '!=', '5'],
            ['id', '!=', '6'],
        ])
            ->select('id', 'singkatan')
            ->get();
        $laborans = User::where('role', 'laboran')
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,singkatan')
            ->orderBy('id')->get();

        return view('dev.ruang.edit', compact('ruang', 'tempats', 'prodis', 'laborans'));
    }

    public function update(Request $request, $id)
    {
        $ruang = Ruang::where('id', $id)->first();

        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:ruangs,kode,' . $ruang->kode,
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
            'is_praktik' => $request->is_praktik,
            'laboran_id' => $request->laboran_id
        ]);

        alert()->success('Success', 'Berhasil memperbarui Ruangan');

        return redirect('dev/ruang');
    }

    public function destroy($id)
    {
        $ruang = Ruang::where('id', $id)->first();
        $ruang->delete();

        alert()->success('Success', 'Berhasil menghapus Ruang Lab');

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
