<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\SubProdi;
use App\Models\User;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index(Request $request)
    {
        $subprodi_id = $request->get('subprodi_id');
        $keyword = $request->get('keyword');
        // 
        $users = User::query()
            ->where('kode', '!=', null)
            ->where('role', 'peminjam')
            ->when($subprodi_id, function ($query) use ($subprodi_id) {
                $query->where('subprodi_id', $subprodi_id);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('kode', 'like', "%$keyword%");
                    $q->orWhere('nama', 'like', "%$keyword%");
                });
            })
            ->select(
                'id',
                'kode',
                'nama',
                'telp',
                'alamat',
                'is_active',
                'tingkat',
                'foto',
                'subprodi_id',
            )
            ->with('subprodi:id,jenjang,nama')
            ->orderBy('subprodi_id')
            ->orderBy('kode')
            ->paginate(10);
        // 
        $subprodis = SubProdi::select(
            'id',
            'jenjang',
            'nama'
        )->get();
        // 
        return view('kalab.mahasiswa.index', compact('users', 'subprodis'));
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'kode',
                'nama',
                'telp',
                'alamat',
                'is_active',
                'tingkat',
                'foto',
                'subprodi_id',
            )
            ->with('subprodi:id,nama,jenjang')
            ->first();

        return view('kalab.mahasiswa.show', compact('user'));
    }
}
