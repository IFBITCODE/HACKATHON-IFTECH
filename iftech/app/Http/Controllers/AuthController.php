<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        // Cria a SESSÃO WEB (necessária para as rotas Blade protegidas por 'auth')
        Auth::login($user);
        $request->session()->regenerate();

        //credencial login (mantido para uso via API/AJAX se precisar)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!!!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }


    public function loginByRole(Request $request, string $role)
    {
        $dados = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $dados['email'])
            ->where('role', $role)
            ->first();

        if (!$user || !Hash::check($dados['password'], $user->password)) {
            return response()->json([
                'message' => 'E-mail, senha ou tipo de conta inválido.'
            ], 401);
        }

        Auth::login($user);
        $request->session()->regenerate();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout realizado com sucesso!']);

    public function loginPrefeitura(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email ou senha inválidos.'
            ])->withInput();
        }

        $request->session()->regenerate();

        if (Auth::user()->role !== 'prefeito') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Acesso permitido apenas para prefeitos.'
            ]);
        }

        return redirect()->route('prefeitura.homePrefeitura');
    }

    public function logoutPrefeitura(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}