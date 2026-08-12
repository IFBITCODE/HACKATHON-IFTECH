<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $dados = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso!',
            'user' => $user,
        ], 201);
    }
}