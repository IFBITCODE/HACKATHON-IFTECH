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

        // 1. Busca parceiros aprovados no Supabase
        $parceirosAprovados = Empreendedor::where('status', 'aprovado')->get();
        
        // 2. Passa os dados para a IA, incluindo a quantidade de moedas que o local oferece
        $textoParceiros = "";
        if ($parceirosAprovados->count() > 0) {
            foreach ($parceirosAprovados as $parceiro) {
                // Pega a quantidade de moedas (se não existir a coluna ou valor, assume 50)
                $moedas = $parceiro->bonus_moedas ?? 50; 
                $textoParceiros .= "- Nome: {$parceiro->nome_fantasia} | Cidade: {$parceiro->cidade} | Recompensa ao visitar: {$moedas} moedas | Descrição: {$parceiro->descricao}\n";
            }
        } else {
            $textoParceiros = "Ainda não temos parceiros cadastrados nesta região.";
        }

        // 3. O "Cérebro" da IA com regras estritas de gamificação
        $mensagemParaIA = "Você é um assistente virtual de turismo criado para o Hackathon IFTECH.
        
        NOSSOS PARCEIROS OFICIAIS CADASTRADOS E SUAS RECOMPENSAS:
        {$textoParceiros}

        REGRAS DE FUNCIONAMENTO:
        1. ESCOPO: Fale APENAS sobre turismo, gastronomia, hospedagem e passeios na região. Recuse educadamente outros assuntos.
        2. RECOMENDAÇÕES: Priorize recomendar os estabelecimentos da lista de 'NOSSOS PARCEIROS OFICIAIS'.
        3. AVISO DE MOEDAS (OBRIGATÓRIO): Sempre que você sugerir um dos nossos parceiros oficiais, você DEVE informar a quantidade exata de moedas que ele oferece. 
           - NUNCA use frases genéricas como 'você ganha moedas de troca'.
           - USE SEMPRE o formato: 'Visitando este local, você ganha X moedas de troca!', substituindo a letra X pelo número exato de moedas informado na lista acima para aquele parceiro.
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
                // Pega o texto puro da resposta
                $respostaBot = $dados['candidates'][0]['content']['parts'][0]['text'] ?? 'Sem resposta da IA.';
            } else {
                $erroDoGoogle = $respostaAPI->json();
                $respostaBot = "Aviso da API: " . ($erroDoGoogle['error']['message'] ?? 'Erro desconhecido');
            }

        } catch (\Exception $e) {
            $respostaBot = "Ocorreu um erro de conexão: " . $e->getMessage();
        }

        // SE A REQUISIÇÃO VIER VIA JAVASCRIPT (AJAX / FETCH)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'resposta' => $respostaBot,
                'mensagemUser' => $mensagemUser
            ]);
        }

        // SE O NAVEGADOR RECARREGAR
        return view('usuario.homeUsuario', [
            'resposta' => $respostaBot,
            'mensagemUser' => $mensagemUser
        ]);
    }
}