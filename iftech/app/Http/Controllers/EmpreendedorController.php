<?php

namespace App\Http\Controllers;

use App\Models\CodigoTroca;
use App\Models\Empreendedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


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

    public function painel()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect('/login-empreendedor');
        }

        $empreendedor = Empreendedor::where('user_id', $user->id)->first();
        if (!$empreendedor) {
            abort(404, 'Empreendedor não encontrado.');
        }

        // Busca apenas os códigos desta empresa (os usados já terão sido deletados pelo turista)
        $codigos = CodigoTroca::where('empreendedor_id', $empreendedor->id)
                              ->latest()
                              ->get();

        return view('empreendedor.controleEmpreendedor', [
            'empreendedor' => $empreendedor,
            'codigos' => $codigos
        ]);
    }

    public function gerarCodigo(Request $request)
    {
        $request->validate([
            'moedas' => 'required|integer|min:1'
        ]);

        $user = Auth::user();
        $empreendedor = Empreendedor::where('user_id', $user->id)->first();

        // Cria o cupom descartável no banco
        $novoCupom = new CodigoTroca();
        $novoCupom->empreendedor_id = $empreendedor->id;
        $novoCupom->codigo = 'RTG-' . strtoupper(Str::random(6)); 
        $novoCupom->moedas = $request->moedas;
        $novoCupom->status = 'disponivel'; 
        $novoCupom->save();

        return response()->json([
            'message' => 'Código gerado com sucesso!',
            'codigo' => $novoCupom->codigo,
            'moedas' => $novoCupom->moedas
        ]);
    }

    public function listarCodigos()
    {
        $user = Auth::user();
        $empreendedor = Empreendedor::where('user_id', $user->id)->first();
        
        $codigos = CodigoTroca::where('empreendedor_id', $empreendedor->id)
                              ->latest()
                              ->get();
                              
        return response()->json($codigos);
    }

   public function usarCodigo(Request $request)
{
    $request->validate(['codigo' => 'required|string']);
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'Você precisa estar logado.'], 401);
    }

    $codigoDigitado = strtoupper(trim($request->codigo));

    DB::beginTransaction();
    try {
        // 1. Busca o código que o turista digitou
        $cupom = CodigoTroca::where('codigo', $codigoDigitado)->lockForUpdate()->first();

        if (!$cupom) {
            DB::rollBack();
            return response()->json(['message' => 'Código inválido ou já resgatado.'], 400);
        }

        // 2. Guarda os dados antes de queimar o código
        $moedasGanhas = $cupom->moedas;
        $empreendedorId = $cupom->empreendedor_id;

        // 3. Atualiza o saldo do turista
        $novoSaldo = (int) ($user->moedas ?? 0) + (int) $moedasGanhas;
        DB::table('users')->where('id', $user->id)->update(['moedas' => $novoSaldo]);

        // 4. QUEIMA O CÓDIGO ATUAL
        $cupom->delete();

        // 5. GERA AUTOMATICAMENTE O PRÓXIMO DA FILA COM O MESMO VALOR
        $proximoCupom = new CodigoTroca();
        $proximoCupom->empreendedor_id = $empreendedorId;
        $proximoCupom->codigo = 'RTG-' . strtoupper(Str::random(6));
        $proximoCupom->moedas = $moedasGanhas;
        $proximoCupom->status = 'disponivel';
        $proximoCupom->save();

        DB::commit();

        return response()->json([
            'message' => 'Resgate realizado com sucesso!',
            'moedas_ganhas' => $moedasGanhas,
            'saldo_atual' => $novoSaldo
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Erro ao resgatar.', 'error' => $e->getMessage()], 500);
    }
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

    public function atualizarValorFila(Request $request)
{
    $request->validate(['moedas' => 'required|integer|min:1']);

    $user = Auth::user();
    $empreendedor = \App\Models\Empreendedor::where('user_id', $user->id)->first();

    // CORREÇÃO AQUI: Adicionamos o ->latest() para pegar o mesmo da tela!
    $cupomFila = CodigoTroca::where('empreendedor_id', $empreendedor->id)
                            ->latest()
                            ->first();

    // Se a fila já existir, apenas atualiza o valor
    if ($cupomFila) {
        $cupomFila->moedas = $request->moedas;
        $cupomFila->save();
    } else {
        // Se a fila estiver vazia (primeiro acesso do empreendedor), cria o primeiro código
        $cupomFila = new CodigoTroca();
        $cupomFila->empreendedor_id = $empreendedor->id;
        $cupomFila->codigo = 'RTG-' . strtoupper(Str::random(6));
        $cupomFila->moedas = $request->moedas;
        $cupomFila->status = 'disponivel';
        $cupomFila->save();
    }

    return response()->json([
        'message' => 'Valor da fila atualizado!',
        'codigo' => $cupomFila->codigo,
        'moedas' => $cupomFila->moedas
    ]);
}
}
