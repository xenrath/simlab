<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use App\Models\Ruang;
use App\Models\Tempat;
use App\Models\User;
use Illuminate\Support\Str;
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

        return view('admin.ruang.index', compact('ruangs', 'prodis'));
    }

    public function create()
    {
        $tempats = Tempat::select('id', 'nama')->get();
        $prodis = Prodi::select('id', 'singkatan')->get();
        $users = User::where('role', 'laboran')
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,singkatan')
            ->get();

        return view('admin.ruang.create', compact('tempats', 'prodis', 'users'));
    }

    public function store(Request $request)
    {
        $validator_laboran_id = 'nullable';

        if ($request->is_praktik) {
            $validator_laboran_id = 'required';
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tempat_id' => 'required',
            'is_praktik' => 'required',
            'prodi_id' => 'required',
            'laboran_id' => $validator_laboran_id,
        ], [
            'nama.required' => 'Nama Ruang harus diisi!',
            'tempat_id.required' => 'Tempat harus dipilih!',
            'is_praktik.required' => 'Untuk Praktik harus dipilih!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal menambahkan Ruang!');
        }

        $kode = strtoupper(Str::random(6));
        Ruang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'prodi_id' => $request->prodi_id,
            'laboran_id' => $request->laboran_id,
            'is_praktik' => $request->is_praktik,
        ]);

        return redirect('admin/ruang')->with('success', 'Berhasil menambahkan Ruang');
    }

    public function edit($id)
    {
        $ruang = Ruang::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'tempat_id',
                'prodi_id',
                'is_praktik',
                'laboran_id'
            )
            ->first();
        $tempats = Tempat::select('id', 'nama')->get();
        $prodis = Prodi::select('id', 'singkatan')->get();
        $users = User::where('role', 'laboran')
            ->select('id', 'nama', 'prodi_id')
            ->with('prodi:id,singkatan')
            ->get();

        return view('admin.ruang.edit', compact('ruang', 'tempats', 'prodis', 'users'));
    }

    public function update(Request $request, $id)
    {
        $validator_laboran_id = 'nullable';

        if ($request->is_praktik) {
            $validator_laboran_id = 'required';
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tempat_id' => 'required',
            'is_praktik' => 'required',
            'prodi_id' => 'required',
            'laboran_id' => $validator_laboran_id,
        ], [
            'nama.required' => 'Nama Ruang harus diisi!',
            'tempat_id.required' => 'Tempat harus dipilih!',
            'is_praktik.required' => 'Untuk Praktik harus dipilih!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->withErrors($validator->errors())
                ->with('error', 'Gagal memperbarui Ruang!');
        }

        Ruang::where('id', $id)->update([
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'prodi_id' => $request->prodi_id,
            'is_praktik' => $request->is_praktik,
            'laboran_id' => $request->laboran_id
        ]);

        return redirect('admin/ruang')->with('success', 'Berhasil memperbarui Ruangan');
    }

    public function destroy($id)
    {
        $ruang = Ruang::where('id', $id)->first();
        $ruang->delete();

        return back()->with('success', 'Berhasil menghapus Ruang');
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
