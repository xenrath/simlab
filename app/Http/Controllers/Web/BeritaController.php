<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Berita;
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
        $this->validateBerita($request, true);

        $namagambar = $this->uploadGambar($request);

        Berita::create([
            'judul' => $request->input('judul'),
            'isi' => $request->input('isi'),
            'gambar' => $namagambar,
            'slug' => Str::slug($request->input('judul')),
        ]);

        alert()->success('Success', 'Berhasil menambahkan Berita');
        return redirect('web/berita');
    }

    public function show($id)
    {
        $berita = Berita::findOrFail($id);
        return view('web.berita.show', compact('berita'));
    }

    public function edit($id)
    {
        $berita = Berita::findOrFail($id);
        return view('web.berita.edit', compact('berita'));
    }

    public function update(Request $request, $id)
    {
        $this->validateBerita($request, false);

        $berita = Berita::findOrFail($id);

        $namagambar = $berita->gambar;
        if ($request->hasFile('gambar')) {
            $namagambar = $this->uploadGambar($request);
        }

        $berita->update([
            'judul' => $request->input('judul'),
            'isi' => $request->input('isi'),
            'gambar' => $namagambar,
            'slug' => Str::slug($request->input('judul')),
        ]);

        alert()->success('Success', 'Berhasil memperbarui Berita');
        return redirect('web/berita');
    }

    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);
        $berita->delete();

        alert()->success('Success', 'Berhasil menghapus Berita');
        return redirect('web/berita');
    }

    // ---------- Private helper functions ----------

    private function validateBerita(Request $request, $isStore = true)
    {
        $rules = [
            'judul' => 'required',
            'isi' => 'required',
            'gambar' => $isStore ? 'required|image|mimes:jpeg,jpg,png|max:2048' : 'nullable|image|mimes:jpeg,jpg,png|max:2048',
        ];

        $messages = [
            'judul.required' => 'Judul berita tidak boleh kosong!',
            'isi.required' => 'Isi tidak boleh kosong!',
            'gambar.required' => 'Gambar harus ditambahkan!',
            'gambar.image' => 'Gambar harus berformat jpeg, jpg, png!',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            alert()->error('Error', $isStore ? 'Gagal menambahkan Berita!' : 'Gagal memperbarui Berita!');
            back()->withInput()->withErrors($validator)->throwResponse();
        }
    }

    private function uploadGambar(Request $request): string
    {
        $original = str_replace(' ', '', $request->file('gambar')->getClientOriginalName());
        $filename = 'berita/' . now()->format('mYdHs') . random_int(1, 10) . '_' . $original;
        $request->file('gambar')->storeAs('public/uploads/', $filename);

        return $filename;
    }
}
        