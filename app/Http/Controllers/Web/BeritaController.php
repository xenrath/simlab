<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::paginate(5);
        return view('web.berita.index', compact('beritas'));
    }

    public function create()
    {
        return view('web.berita.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'judul.required' => 'Judul berita tidak boleh kosong!',
            'isi.required' => 'Isi tidak boleh kosong!',
            'gambar.required' => 'Gambar harus ditambahkan!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('error', $error);
        }

        $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
        $namagambar = 'berita/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
        $request->gambar->storeAs('public/uploads/', $namagambar);

        Berita::create(array_merge($request->all(), [
            'gambar' => $namagambar,
            'slug' => Str::slug($request->judul)
        ]));

        alert()->success('Success', 'Berhasil menambahkan Berita');

        return redirect('web/berita');
    }

    public function show($id)
    {
        $berita = Berita::where('id', $id)->first();

        return view('web.berita.show', compact('berita'));
    }

    public function edit($id)
    {
        $berita = Berita::where('id', $id)->first();

        return view('web.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ], [
            'judul.required' => 'Judul berita tidak boleh kosong!',
            'isi.required' => 'Isi tidak boleh kosong!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all();
            return back()->withInput()->with('status', $error);
        }

        $berita = Berita::where('id', $id)->first();

        if ($request->gambar) {
            $gambar = str_replace(' ', '', $request->gambar->getClientOriginalName());
            $namagambar = 'berita/' . date('mYdHs') . random_int(1, 10) . '_' . $gambar;
            $request->gambar->storeAs('public/uploads/', $namagambar);
        } else {
            $namagambar = $berita->gambar;
        }

        Berita::where('id', $id)->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'gambar' => $namagambar,
            'slug' => Str::slug($request->judul)
        ]);

        alert()->success('Success', 'Berhasil memperbarui Berita');

        return redirect('web/berita');
    }

    public function destroy($id)
    {
        $berita = Berita::where('id', $id)->first();
        $berita->delete();

        alert()->success('Success', 'Berhasil menghapus Berita');

        return redirect('web/berita');
    }
}
