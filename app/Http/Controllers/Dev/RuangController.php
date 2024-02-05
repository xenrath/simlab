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
        $tempats = Tempat::select('id', 'nama')->get();
        $prodis = Prodi::where('is_prodi', true)->select('id', 'singkatan')->get();

        if (request()->get('is_praktik')) {
            $users = User::where('role', 'laboran')
                ->select('id', 'nama')
                ->get();
            return view('dev.ruang.create_is_praktik_true', compact('tempats', 'prodis', 'users'));
        } else {
            return view('dev.ruang.create_is_praktik_false', compact('tempats', 'prodis'));
        }
    }

    public function store(Request $request)
    {
        if ($request->is_praktik == '1') {
            return $this->store_is_praktik_true($request);
        } elseif ($request->is_praktik == '0') {
            return $this->store_is_praktik_false($request);
        } else {
            alert()->error('Error', 'Gagal menambahkan Ruang!');
            return back()->withInput();
        }
    }

    public function store_is_praktik_true($request)
    {
        $validator = Validator::make($request->all(), [
            'is_praktik' => 'required',
            'nama' => 'required',
            'tempat_id' => 'required',
            'prodi_id' => 'required',
            'laboran_id' => 'required'
        ], [
            'is_praktik.required' => 'Is Praktik kosong!',
            'nama.required' => 'Nama ruangan harus diisi!',
            'tempat_id.required' => 'Tempat harus dipilih!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = $this->generateCode();

        Ruang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'prodi_id' => $request->prodi_id,
            'laboran_id' => $request->laboran_id,
            'is_praktik' => true,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Ruang');

        return redirect('dev/ruang');
    }

    public function store_is_praktik_false($request)
    {
        $validator = Validator::make($request->all(), [
            'is_praktik' => 'required',
            'nama' => 'required',
            'tempat_id' => 'required',
            'prodi_id' => 'required',
        ], [
            'is_praktik.required' => 'Is Praktik kosong!',
            'nama.required' => 'Nama ruangan harus diisi!',
            'tempat_id.required' => 'Tempat harus dipilih!',
            'prodi_id.required' => 'Prodi harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $kode = $this->generateCode();

        Ruang::create([
            'kode' => $kode,
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'prodi_id' => $request->prodi_id,
            'is_praktik' => false,
        ]);

        alert()->success('Success', 'Berhasil menambahkan Ruang');

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
                'prodi_id',
                'is_praktik',
                'laboran_id'
            )
            ->first();
        $tempats = Tempat::select('id', 'nama')->get();
        $prodis = Prodi::where('is_prodi', true)->select('id', 'singkatan')->get();

        if ($ruang->is_praktik) {
            $users = User::where('role', 'laboran')
                ->select('id', 'nama')
                ->get();
            return view('dev.ruang.edit_is_praktik_true', compact('ruang', 'tempats', 'prodis', 'users'));
        } else {
            return view('dev.ruang.edit_is_praktik_false', compact('ruang', 'tempats', 'prodis'));
        }
    }

    public function update(Request $request, $id)
    {
        $is_praktik = Ruang::where('id', $id)->value('is_praktik');

        if ($is_praktik) {
            return $this->update_is_praktik_true($request, $id);
        } else {
            return $this->update_is_praktik_false($request, $id);
        }
    }

    public function update_is_praktik_true($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tempat_id' => 'required',
            'prodi_id' => 'required',
            'laboran_id' => 'required'
        ], [
            'nama.required' => 'Nama ruangan harus diisi!',
            'tempat_id.required' => 'Tempat harus dipilih!',
            'prodi_id.required' => 'Prodi harus dipilih!',
            'laboran_id.required' => 'Laboran harus dipilih!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Ruang::where('id', $id)->update([
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'prodi_id' => $request->prodi_id,
            'laboran_id' => $request->laboran_id
        ]);

        alert()->success('Success', 'Berhasil memperbarui Ruangan');

        return redirect('dev/ruang');
    }

    public function update_is_praktik_false($request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'tempat_id' => 'required',
            'prodi_id' => 'required',
        ], [
            'nama.required' => 'Nama ruangan harus diisi!',
            'tempat_id.required' => 'Tempat harus dipilih!',
            'prodi_id.required' => 'Prodi harus dipilih!'
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        Ruang::where('id', $id)->update([
            'nama' => $request->nama,
            'tempat_id' => $request->tempat_id,
            'prodi_id' => $request->prodi_id
        ]);

        alert()->success('Success', 'Berhasil memperbarui Ruangan');

        return redirect('dev/ruang');
    }

    public function destroy($id)
    {
        $ruang = Ruang::where('id', $id)->first();
        $ruang->delete();

        alert()->success('Success', 'Berhasil menghapus Ruang');

        return back();
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
