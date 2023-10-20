<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pinjam;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function list_user()
    {
        $users = User::get();

        if (count($users) > 0) {
            return response()->json(['data' => $users]);
        } else {
            return response()->json(['data' => null]);
        }
    }
}
