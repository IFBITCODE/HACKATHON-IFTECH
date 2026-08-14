<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Empreendedor; 

class ChatbotController extends Controller
{
    public function index() {
        return view('usuario.homeUsuario', [
            'resposta' => null,
            'mensagemUser' => null
        ]);
    }

    public function responder(Request $request) {
        $mensagemUser = $request->input('mensagem'); 
        $apiKey = env('GEMINI_API_KEY');

        // 1. Busca parceiros aprovados
        $parceirosAprovados = Empreendedor::where('status', 'aprovado')->get();
        
        // 2. Prepara os dados e mastiga a frase das moedas no próprio PHP
        $textoParceiros = "";
        if ($parceirosAprovados->count() > 0) {
            foreach ($parceirosAprovados as $parceiro) {
                
                // NOVA LÓGICA: Busca as moedas na tabela de cupons!
                // Ele soma o valor de todos os códigos disponíveis vinculados a esta empresa
                $moedas = \Illuminate\Support\Facades\DB::table('codigos_troca')
                            ->where('empreendedor_id', $parceiro->id)
                            ->where('status', 'disponivel')
                            ->sum('moedas'); 
                
                // Garante que se não tiver nada, seja 0
                $moedas = $moedas ?? 0; 
                
                // Regra de negócio: Tem moeda disponível ou não?
                if ($moedas > 0) {
                    $avisoMoedas = "Visitando este local, você ganha {$moedas} moedas de troca!";
                } else {
                    $avisoMoedas = "Esse restaurante ainda não tem moedas bônus.";
                }

                $textoParceiros .= "- Nome: {$parceiro->nome_fantasia} | Cidade: {$parceiro->cidade} | Aviso de Moedas: {$avisoMoedas} | Descrição: {$parceiro->descricao}\n";
            }
        } else {
            $textoParceiros = "Ainda não temos parceiros cadastrados nesta região.";
        }

        // 3. O "Cérebro" da IA com a instrução de COPIAR E COLAR
        $mensagemParaIA = "Você é um assistente virtual de turismo criado para o Hackathon IFTECH.
        
        NOSSOS PARCEIROS OFICIAIS CADASTRADOS:
        {$textoParceiros}

        REGRAS DE FUNCIONAMENTO:
        1. ESCOPO: Fale APENAS sobre turismo, gastronomia, hospedagem e passeios na região. Recuse educadamente outros assuntos.
        2. RECOMENDAÇÕES: Priorize recomendar os estabelecimentos da lista de 'NOSSOS PARCEIROS OFICIAIS'.
        3. AVISO DE MOEDAS (OBRIGATÓRIO): Sempre que você sugerir um parceiro oficial, você DEVE copiar e colar EXATAMENTE a frase que está escrita no campo 'Aviso de Moedas' daquele parceiro. Não altere os números e não invente frases.
        4. TAMANHO: Seja amigável e direto ao ponto (máximo de 2 parágrafos).

        Mensagem do usuário: " . $mensagemUser;

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

        try {
            $respostaAPI = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->post($url, [
                'contents' => [
                    ['parts' => [['text' => $mensagemParaIA]]]
                ]
            ]);

            if ($respostaAPI->successful()) {
                $dados = $respostaAPI->json();
                
                // CORREÇÃO PARA O VS CODE: O helper data_get() do Laravel acessa com segurança.
                // Se não encontrar o caminho exato, ele retorna 'Sem resposta da IA.' sem gerar erro.
                $respostaBot = data_get($dados, 'candidates.0.content.parts.0.text', 'Sem resposta da IA.');
                
            } else {
                $erroDoGoogle = $respostaAPI->json();
                $respostaBot = "Aviso da API: " . data_get($erroDoGoogle, 'error.message', 'Erro desconhecido');
            }

        } catch (\Exception $e) {
            $respostaBot = "Ocorreu um erro de conexão: " . $e->getMessage();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'resposta' => $respostaBot,
                'mensagemUser' => $mensagemUser
            ]);
        }

        return view('usuario.homeUsuario', [
            'resposta' => $respostaBot,
            'mensagemUser' => $mensagemUser
        ]);
    }
}