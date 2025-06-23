<?php

namespace App\Http\Controllers\Kalab;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LaboranController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'laboran')
            ->select(
                'id',
                'nama',
                'telp',
                'alamat',
                'foto',
                'prodi_id'
            )
            ->with('prodi:id,singkatan')
            ->with('ruangs:laboran_id,nama')
            ->paginate(10);
        // 
        return view('kalab.laboran.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->select(
                'id',
                'nama',
                'telp',
                'alamat',
                'foto',
                'prodi_id'
            )
            ->with('prodi:id,singkatan', 'ruangs:laboran_id,nama')
            ->first();

        return view('kalab.laboran.show', compact('user'));
    }
}
