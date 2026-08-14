<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Empreendedor; 

class ChatbotController extends Controller
{
    // 1. Abre a tela inicial de busca (homeUsuario)
    public function index() {
        return view('usuario.homeUsuario', [
            'resposta' => null,
            'mensagemUser' => null
        ]);
    }

    // 2. Processa a pesquisa e retorna mantendo a mesma página
    public function responder(Request $request) {
        $mensagemUser = $request->input('mensagem'); 
        $apiKey = env('GEMINI_API_KEY');

        // 1. Busca no banco de dados apenas as empresas aprovadas pela prefeitura
        $parceirosAprovados = Empreendedor::where('status', 'aprovado')->get();
        
        // 2. Transforma os dados do banco em um texto que a IA consiga ler
        $textoParceiros = "";
        if ($parceirosAprovados->count() > 0) {
            foreach ($parceirosAprovados as $parceiro) {
                $textoParceiros .= "- Nome: {$parceiro->nome_fantasia} | Cidade: {$parceiro->cidade} | Descrição: {$parceiro->descricao}\n";
            }
        } else {
            $textoParceiros = "Ainda não temos parceiros cadastrados nesta região.";
        }

        // 3. O "Cérebro" da IA com regras de Gamificação e Escopo Flexível
        $mensagemParaIA = "Você é um assistente virtual de turismo criado para o Hackathon IFTECH.
        
        NOSSOS PARCEIROS OFICIAIS CADASTRADOS:
        {$textoParceiros}

        REGRAS DE FUNCIONAMENTO:
        1. ESCOPO: Você deve falar APENAS sobre turismo, gastronomia, hospedagem e passeios na região (como, por exemplo, opções em Campina Grande e arredores). Se o usuário perguntar algo totalmente fora desse universo (como matemática, programação, consertos, etc.), recuse dizendo: 'Desculpe, meu conhecimento é limitado apenas ao turismo municipal.'
        2. RECOMENDAÇÕES (PRIORIDADE): Se o usuário pedir dicas de onde ir (comer, dormir, passear), primeiro verifique se há algum estabelecimento na lista de 'NOSSOS PARCEIROS OFICIAIS' que atenda ao pedido.
           - Se houver: Recomende-o e adicione OBRIGATORIAMENTE a frase exata: 'se voce for nesse que é nosso parceiro voce pode ganhar algumas moedas de troca'. Em seguida, dê mais 1 opção de conhecimento geral para dar variedade.
        3. SEM PARCEIROS: Se o usuário pedir algo (ex: restaurante) e a lista de parceiros não tiver nenhum restaurante cadastrado, não bloqueie a conversa. Apenas recomende 2 lugares reais e legais da cidade usando seu conhecimento geral.
        4. TAMANHO: Seja amigável, direto ao ponto (máximo 2 parágrafos) e responda em pt-BR.

        Mensagem do usuário: " . $mensagemUser;

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

        try {
            $respostaAPI = Http::withoutVerifying()->withHeaders([
                'Content-Type' => 'application/json',
                'X-goog-api-key' => $apiKey,
            ])->post($url, [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $mensagemParaIA]
                        ]
                    ]
                ]
            ]);

            if ($respostaAPI->successful()) {
                $dados = $respostaAPI->json();
                $respostaBot = $dados['candidates'][0]['content']['parts'][0]['text'] ?? 'Sem resposta da IA.';
            } else {
                $erroDoGoogle = $respostaAPI->json();
                $mensagemDeErro = $erroDoGoogle['error']['message'] ?? 'Erro desconhecido na API';
                $respostaBot = "Aviso da API: " . $mensagemDeErro;
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

        // SE O NAVEGADOR RECARREGAR (SUBMIT TRADICIONAL)
        // Retorna a view correta da sua home com os resultados inseridos abaixo da busca
        return view('usuario.homeUsuario', [
            'resposta' => $respostaBot,
            'mensagemUser' => $mensagemUser
        ]);
    }
}