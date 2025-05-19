<?php

namespace App\Http\Controllers\Laboran;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    public function index()
    {
        if (auth()->user()->ruangs->first()->tempat_id == '1') {
            return $this->index_lab_terpadu();
        } else if (auth()->user()->ruangs->first()->tempat_id == '2') {
            return $this->index_farmasi();
        };
    }

    public function index_lab_terpadu()
    {
        $menunggu = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'menunggu']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();
        $proses = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'disetujui']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();
        $selesai = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'selesai']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();
        $tagihan = Pinjam::where([
            ['kategori', 'normal'],
            ['status', 'tagihan']
        ])
            ->where(function ($query) {
                $query->where('laboran_id', auth()->user()->id);
                $query->orWhereHas('ruang', function ($query) {
                    $query->where('laboran_id', auth()->user()->id);
                });
            })
            ->count();

        return view('laboran.index_lab_terpadu', compact(
            'menunggu',
            'proses',
            'selesai',
            'tagihan'
        ));
    }

    public function index_farmasi()
    {
        return view('laboran.index');
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'telp' => 'nullable|unique:users,telp,' . auth()->user()->id . ',id',
        ], [
            'nama.required' => 'Nama Lengkap harus diisi!',
            'telp.unique' => 'Nomor WhatsApp sudah digunakan!',
        ]);
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Profile!');
            return back()->withInput()->withErrors($validator->errors())->with('profile', true);
        }
        // 
        $update = User::where('id', auth()->user()->id)->update([
            'nama' => $request->nama,
            'telp' => $request->telp,
        ]);

        if ($update) {
            alert()->success('Success', 'Berhasil memperbarui Profile');
        } else {
            alert()->error('Error', 'Gagal memperbarui Profile!');
        }

        return back();
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'Password Baru harus diisi!',
            'password.confirmed' => 'Konfirmasi Password tidak sesuai!',
            'password_confirmation.required' => 'Konfirmasi Password harus diisi!',
        ]);
        // 
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Password!');
            return back()->withInput()->withErrors($validator->errors())->with('password', true);
        }
        // 
        $user = User::where('id', auth()->user()->id)->update([
            'password' => bcrypt($request->password),
            'password_text' => $request->password,
        ]);
        // 
        if ($user) {
            alert()->success('Success', 'Berhasil memperbarui Profile');
        } else {
            alert()->error('Error', 'Gagal memperbarui Profile!');
        }
        // 
        return back();
    }

    public function hubungi($id)
    {
        $telp = User::where('id', $id)->value('telp');

        $agent = new Agent;
        $desktop = $agent->isDesktop();

        if ($desktop) {
            return redirect()->away('https://web.whatsapp.com/send?phone=+62' . $telp);
        } else {
            return redirect()->away('https://wa.me/+62' . $telp);
        }
    }

    public function get_barang(Request $request)
    {
        $items = $request->items;
        if ($items) {
            $barangs = Barang::whereIn('id', $items)->with('satuan')->orderBy('kategori', 'DESC')->orderBy('nama', 'ASC')->get();
        } else {
            $barangs = null;
        }

        return json_encode($barangs);
    }
}
