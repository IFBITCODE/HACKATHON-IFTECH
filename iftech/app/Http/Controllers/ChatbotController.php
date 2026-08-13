<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index() {
        return view('chat');
    }

   public function responder(Request $request) {
        $mensagemUser = $request->input('mensagem'); 
        $apiKey = env('GEMINI_API_KEY');

        // 1. Variável para guardar o texto do seu arquivo v3
        // IMPORTANTE: Substitua o texto abaixo pelo conteúdo real do seu arquivo v3
        $contextoV3 = "COLE TODO O CONTEÚDO DO SEU ARQUIVO V3 AQUI. Pode ser um texto longo explicando o que é o projeto, como ele funciona, etc.";

        // 2. A MÁGICA AQUI: Instruções super restritivas (Filtros)
        $mensagemParaIA = "Você é um assistente virtual estrito criado para ser um especialista em turismo municipal de Joao Pessoa - PB.
        
        Regras de Ouro:
        1. BASE DE CONHECIMENTO: Use ÚNICA e EXCLUSIVAMENTE o seguinte texto para formular suas respostas: '{$contextoV3}'.
        2. FORA DE ESCOPO: Se o usuário perguntar qualquer coisa que não esteja no texto acima (como receitas, curiosidades, 'como fazer uma pipa', matemática, etc.), você DEVE recusar a responder dizendo exatamente: 'Desculpe, meu conhecimento é limitado apenas ao turismo municipal.'
        3. TAMANHO: Seja extremamente direto e conciso. Suas respostas não podem ultrapassar 1 parágrafo curto. Vá direto ao ponto.
        4. IDIOMA: Responda obrigatoriamente em Português do Brasil (pt-BR).
        

        Pergunta do usuário: " . $mensagemUser;

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
                $respostaBot = $dados['candidates'][0]['content']['parts'][0]['text'];
            } else {
                $erroDoGoogle = $respostaAPI->json();
                $mensagemDeErro = $erroDoGoogle['error']['message'] ?? 'Erro desconhecido na API';
                $respostaBot = "O Google negou o acesso. Motivo: " . $mensagemDeErro;
            }

        } catch (\Exception $e) {
            $respostaBot = "Ocorreu um erro de conexão: " . $e->getMessage();
        }

        return view('chat', [
            'resposta' => $respostaBot,
            'mensagemUser' => $mensagemUser
        ]);
    }
}