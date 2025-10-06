<?php

namespace App\Http\Controllers\Peminjam;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DetailPinjam;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     if (auth()->user()->isLabTerpadu()) {
    //         if (auth()->user()->isFeb()) {
    //             return redirect('peminjam/feb');
    //         } else {
    //             return redirect('peminjam/labterpadu');
    //         }
    //     } elseif (auth()->user()->isFarmasi()) {
    //         return redirect('peminjam/farmasi');
    //     }
    // }

    public function index()
    {
        if (auth()->user()->isBidan()) {
            return redirect('peminjam/bidan');
        } elseif (auth()->user()->isPerawat()) {
            return redirect('peminjam/perawat');
        } elseif (auth()->user()->isK3()) {
            return redirect('peminjam/k3');
        } elseif (auth()->user()->isFarmasi()) {
            return redirect('peminjam/farmasi');
        }
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'telp' => 'nullable|unique:users,telp,' . auth()->user()->id . ',id',
        ], [
            'telp.unique' => 'Nomor WhatsApp sudah digunakan!',
        ]);
        
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Profile!');
            return back()->withInput()->withErrors($validator->errors())->with('profile', true);
        }
        
        $update = User::where('id', auth()->user()->id)->update([
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
        
        if ($validator->fails()) {
            alert()->error('Error', 'Gagal memperbarui Password!');
            return back()->withInput()->withErrors($validator->errors())->with('password', true);
        }
        
        $user = User::where('id', auth()->user()->id)->update([
            'password' => bcrypt($request->password),
            'password_text' => $request->password,
        ]);
        
        if ($user) {
            alert()->success('Success', 'Berhasil memperbarui Profile');
        } else {
            alert()->error('Error', 'Gagal memperbarui Profile!');
        }
        
        return back();
    }

    public function search_items(Request $request)
    {
        $keyword = $request->input('keyword');
        $limit = (int) $request->input('page', 10); // default ke 10 jika tidak dikirim

        $barangs = Barang::whereHas('ruang', function ($query) {
            $query->where('tempat_id', 1);
        })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('nama', 'like', "%{$keyword}%");
            })
            ->select('id', 'nama', 'ruang_id')
            ->with('ruang:id,nama')
            ->orderBy('nama')
            ->take($limit)
            ->get();

        return $barangs;
    }

    public function add_item($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->first();

        return $barang;
    }

    public function delete_item($id)
    {
        if (DetailPinjam::where('id', $id)->exists()) {
            $detail_pinjam = DetailPinjam::findOrFail($id);
            $detail_pinjam->delete();
        }

        return true;
    }

    public function anggota_search(Request $request)
    {
        $keyword = $request->input('keyword');
        $limit = (int) $request->input('page', 10);
        $current_user_id = auth()->id();
        $subprodi_id = auth()->user()->subprodi_id;

        $users = User::where('role', 'peminjam')
            ->where('subprodi_id', $subprodi_id)
            ->where('id', '!=', $current_user_id)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%")
                        ->orWhere('kode', 'like', "%{$keyword}%");
                });
            })
            ->select('id', 'kode', 'nama')
            ->orderByDesc('kode')
            ->take($limit)
            ->get();

        return $users;
    }

    public function add_anggota($id)
    {
        $user = User::where('id', $id)->select('id', 'kode', 'nama')->first();
        return $user;
    }

    public function search_farmasi(Request $request)
    {
        $ruang_id = $request->keyword_ruang_id;
        $nama = $request->keyword_barang_nama;

        if ($ruang_id && $nama) {
            $barangs = Barang::where([
                ['ruang_id', $ruang_id],
                ['nama', 'like', "%$nama%"]
            ])
                ->select(
                    'id',
                    'nama',
                    'ruang_id',
                )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->take(10)
                ->get();
        } elseif ($ruang_id) {
            $barangs = Barang::where('ruang_id', $ruang_id)
                ->select(
                    'id',
                    'nama',
                    'ruang_id'
                )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->take(10)
                ->get();
        } elseif ($nama) {
            $barangs = Barang::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '2');
            })
                ->where('nama', 'like', "%$nama%")
                ->select(
                    'id',
                    'nama',
                    'ruang_id',
                )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->take(10)
                ->get();
        } else {
            $barangs = Barang::whereHas('ruang', function ($query) {
                $query->where('tempat_id', '2');
            })
                ->select(
                    'id',
                    'nama',
                    'ruang_id',
                )
                ->with('ruang:id,nama')
                ->orderBy('nama')
                ->take(10)
                ->get();
        }

        return $barangs;
    }

    public function barang_get($id)
    {
        $barang = Barang::where('id', $id)
            ->select(
                'id',
                'nama',
                'ruang_id'
            )
            ->with('ruang:id,nama')
            ->first();
            
        return $barang;
    }

    public function estafet_get($id)
    {
        $barangs = DetailPinjam::where('detail_pinjams.pinjam_id', $id)
            ->join('barangs', 'detail_pinjams.barang_id', '=', 'barangs.id')
            ->join('ruangs', 'barangs.ruang_id', '=', 'ruangs.id')
            ->select(
                'barangs.id',
                'barangs.nama',
                'ruang_id',
                'detail_pinjams.jumlah'
            )
            ->with('ruang:id,nama')
            ->get();
        return $barangs;
    }
}
