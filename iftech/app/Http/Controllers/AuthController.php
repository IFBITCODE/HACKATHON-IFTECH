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
        'role' => 'sometimes|in:turista,empreendedor',
    ]);

    $user = User::create([
        'name' => $dados['name'],
        'email' => $dados['email'],
        'password' => Hash::make($dados['password']),
        'role' => $dados['role'] ?? 'turista',
    ]);

    return response()->json([
        'message' => 'Usuário criado com sucesso!',
        'user' => $user,
    ], 201);
}

    public function login(Request $request){

        //validação do email e senha
        $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        //buscar o user no bd
        $user = User::where('email', $dados['email'])->first();

        //verificação da senha
        if(!$user || !Hash::check($dados['password'], $user->password)){
            return response()->json([
                'message' => 'Email ou senha inválido!!!'
            ], 401);
        }

        //credencial login
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!!!',
            'token' => $token,
        ]);
    }
}