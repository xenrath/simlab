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

        if ($subprodi_id != "" && $keyword != "") {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam'],
                ['subprodi_id', $subprodi_id],
            ])->where(function ($query) use ($keyword) {
                $query->where('kode', 'like', "%$keyword%");
                $query->orWhere('nama', 'like', "%$keyword%");
            })
                ->select('id', 'kode', 'nama', 'subprodi_id')
                ->with('subprodi:id,jenjang,nama')
                ->orderBy('subprodi_id')
                ->orderBy('kode')
                ->paginate(10);
        } else if ($subprodi_id != "" && $keyword == "") {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam'],
                ['subprodi_id', $subprodi_id],
            ])
                ->select('id', 'kode', 'nama', 'subprodi_id')
                ->with('subprodi:id,jenjang,nama')
                ->orderBy('subprodi_id')
                ->orderBy('kode')
                ->paginate(10);
        } else if ($subprodi_id == "" && $keyword != "") {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam'],
            ])->where(function ($query) use ($keyword) {
                $query->where('kode', 'like', "%$keyword%");
                $query->orWhere('nama', 'like', "%$keyword%");
            })
                ->select('id', 'kode', 'nama', 'subprodi_id')
                ->with('subprodi:id,jenjang,nama')
                ->orderBy('subprodi_id')
                ->orderBy('kode')
                ->paginate(10);
        } else {
            $users = User::where([
                ['kode', '!=', null],
                ['role', 'peminjam']
            ])
                ->select('id', 'kode', 'nama', 'subprodi_id')
                ->with('subprodi:id,jenjang,nama')
                ->orderBy('subprodi_id')
                ->orderBy('kode')
                ->paginate(10);
        }

        $subprodis = SubProdi::select(
            'id',
            'jenjang',
            'nama'
        )->get();

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
