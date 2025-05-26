<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('roles')->paginate(10));
    }

    public function show($id) {
        $user = User::with('roles')->findOrFail($id);
        return response()->json($user);
    }
    
}
