<?php

namespace App\Http\Controllers;

use App\Models\CodigoTroca;
use App\Models\Empreendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



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
        $empreendedor = Empreendedor::where('user_id', Auth::id())->first();

        if (!$empreendedor) {
            return redirect('/login-empreendedor')
                ->with('error', 'Nenhum cadastro de empreendedor encontrado para esta conta.');
        }

        $codigos = CodigoTroca::where('empreendedor_id', $empreendedor->id)
            ->latest()
            ->get();

        return view('empreendedor.controleEmpreendedor', [
            'empreendedor' => $empreendedor,
            'codigos' => $codigos,
        ]);
    }

    public function gerarCodigo(Request $request)
    {
        $dados = $request->validate([
            'moedas' => 'required|integer|min:1|max:10000',
        ]);

        $empreendedor = Empreendedor::where('user_id', Auth::id())->first();

        if (!$empreendedor) {
            return response()->json([
                'message' => 'Cadastro de empreendedor não encontrado.'
            ], 404);
        }

        if ($empreendedor->status !== 'aprovado') {
            return response()->json([
                'message' => 'Você só pode gerar códigos depois que seu cadastro for aprovado.'
            ], 403);
        }

        do {
            $codigo = 'RTG-' . strtoupper(Str::random(8));
        } while (CodigoTroca::where('codigo', $codigo)->exists());

        $codigoTroca = CodigoTroca::create([
            'empreendedor_id' => $empreendedor->id,
            'codigo' => $codigo,
            'moedas' => $dados['moedas'],
            'status' => 'disponivel',
        ]);

        return response()->json([
            'message' => 'Código gerado com sucesso!',
            'codigo' => $codigoTroca->codigo,
            'moedas' => $codigoTroca->moedas,
        ], 201);
    }

    public function listarCodigos(Request $request)
    {
        $empreendedor = Empreendedor::where('user_id', Auth::id())->firstOrFail();

        return response()->json(
            CodigoTroca::where('empreendedor_id', $empreendedor->id)
                ->latest()
                ->get()
        );
    }

    public function usarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:12',
        ]);

        $codigo = CodigoTroca::where('codigo', strtoupper(trim($request->codigo)))
            ->where('status', 'disponivel')
            ->first();

        if (!$codigo) {
            return response()->json([
                'message' => 'Código inválido ou já utilizado.'
            ], 422);
        }

        $codigo->update([
            'status' => 'utilizado',
            'user_id' => Auth::id(),
            'utilizado_em' => now(),
        ]);

        return response()->json([
            'message' => 'Código utilizado com sucesso!',
            'codigo' => $codigo->codigo,
            'empreendedor_id' => $codigo->empreendedor_id,
        ]);
    }

    public function salvarCupomUnico(Request $request)
{
    $request->validate([
        'moedas' => 'required|integer|min:1',
        'ativo' => 'required|boolean'
    ]);

    // Pega o usuário logado e o empreendedor vinculado a ele
    $user = \Illuminate\Support\Facades\Auth::user();
    $empreendedor = Empreendedor::where('user_id', $user->id)->first();

    if (!$empreendedor) {
        return response()->json(['message' => 'Empreendedor não encontrado.'], 404);
    }

    // Busca o cupom dessa empresa. Se não achar, prepara para criar um novo.
    $cupom = CodigoTroca::firstOrNew(['empreendedor_id' => $empreendedor->id]);

    // Se o cupom for novo (não existir no banco), gera um código aleatório
    if (!$cupom->exists) {
        $cupom->codigo = 'RTG-' . strtoupper(Str::random(6)); // Ex: RTG-A1B2C3
    }

    // Atualiza os valores
    $cupom->moedas = $request->moedas;
    $cupom->status = $request->ativo ? 'disponivel' : 'inativo'; // 'inativo' significa que o bot não vai enxergar
    
    // Salva no Supabase
    $cupom->save();

    return response()->json([
        'message' => 'Cupom salvo com sucesso!',
        'codigo' => $cupom->codigo,
        'moedas' => $cupom->moedas,
        'status' => $cupom->status
    ]);
}

    public function destroy($id)
    {
        $empreendedor = Empreendedor::findOrFail($id);
        $empreendedor->delete();

        return response()->json(['message' => 'Empreendedor removido.']);
    }
}
