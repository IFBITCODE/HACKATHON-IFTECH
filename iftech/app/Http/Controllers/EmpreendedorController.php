<?php

namespace App\Http\Controllers;

use App\Models\Empreendedor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpreendedorController extends Controller
{
    public function index(Request $request)
    {
        $query = Empreendedor::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest()->get());
    }

    public function show($id)
    {
        $empreendedor = Empreendedor::findOrFail($id);
        return response()->json($empreendedor);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'user_id' => 'required|exists:users,id',
            'nome_fantasia' => 'required|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'cpf_cnpj' => 'required|string|unique:empreendedores,cpf_cnpj',
            'category_id' => 'nullable|exists:categories,id',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'bairro' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:255',
            'estado' => 'nullable|string|size:2',
            'cep' => 'nullable|string|max:10',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'descricao' => 'nullable|string',
            'horario_funcionamento' => 'nullable|string|max:255',
            'acessivel' => 'boolean',
            'recursos_acessibilidade' => 'nullable|string',
        ]);

        $dados['status'] = 'pendente';

        $empreendedor = Empreendedor::create($dados);

        return response()->json([
            'message' => 'Cadastro enviado para análise!',
            'empreendedor' => $empreendedor,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $empreendedor = Empreendedor::findOrFail($id);

        // NOVA TRAVA DE SEGURANÇA
        if ($empreendedor->status !== 'aprovado') {
            return response()->json([
                'message' => 'Ação não permitida. Seu cadastro ainda não foi aprovado pelo município.'
            ], 403);
        }

        $dados = $request->validate([
            'nome_fantasia' => 'sometimes|string|max:255',
            'razao_social' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'endereco' => 'nullable|string|max:255',
            'descricao' => 'nullable|string',
            'horario_funcionamento' => 'nullable|string|max:255',
        ]);

        $dados['data_ultima_atualizacao'] = now();
        $empreendedor->update($dados);

        return response()->json([
            'message' => 'Empreendedor atualizado!',
            'empreendedor' => $empreendedor,
        ]);
    }

    public function alterarStatus(Request $request, $id)
    {
        $empreendedor = Empreendedor::findOrFail($id);

        $dados = $request->validate([
            'status' => 'required|in:pendente,aprovado,rejeitado,suspenso',
            'motivo_rejeicao' => 'nullable|string',
        ]);

        $empreendedor->update($dados);

        return response()->json([
            'message' => 'Status atualizado!',
            'empreendedor' => $empreendedor,
        ]);
    }

    public function painel(Request $request)
    {
        $empreendedor = null;

        // 1. TENTA BUSCAR PELO USUÁRIO AUTENTICADO NO LARAVEL (Ideal para produção)
        if (Auth::check()) {
            $empreendedor = Empreendedor::where('user_id', Auth::id())->first();
        }

        // 2. TENTA BUSCAR PELO E-MAIL VINDO DA URL (Query String ?email=exemplo@email.com)
        if (!$empreendedor && $request->has('email')) {
            $user = User::where('email', $request->query('email'))->first();
            if ($user) {
                $empreendedor = Empreendedor::where('user_id', $user->id)->first();
            }
        }

        // 3. FALLBACK HACKATHON: Pega o último cadastrado se os anteriores falharem
        if (!$empreendedor) {
            $empreendedor = Empreendedor::latest()->first();
        }

        // 4. CRIA UM OBJETO DUMMY (MOCK) SE O BANCO ESTIVER TOTALMENTE VAZIO
        // Isso impede 100% o Erro 500 ou Tela Preta por variável indefinida na View.
        if (!$empreendedor) {
            $empreendedor = new Empreendedor([
                'nome_fantasia' => 'Empreendedor Teste',
                'status'        => 'pendente',
                'descricao'     => 'Nenhum cadastro encontrado no banco de dados ainda.'
            ]);
        }

        // 5. Envia a variável $empreendedor garantida para a View
        return view('empreendedor.controleEmpreendedor', [
            'empreendedor' => $empreendedor
        ]);
    }

    public function destroy($id)
    {
        $empreendedor = Empreendedor::findOrFail($id);
        $empreendedor->delete();

        return response()->json(['message' => 'Empreendedor removido.']);
    }
}